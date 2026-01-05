<?php

namespace App\Services\Drivers;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class TasyDriver implements HealthSystemInterface
{
    protected $connection = 'tenant_erp';
    protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    // -------------------------------------------------------------------------
    // BUSCAR PACIENTE (LOGIN)
    // -------------------------------------------------------------------------
    public function buscarPaciente(string $cpf, string $nascimento)
    {
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
        
        return DB::connection($this->connection)
            ->table('PESSOA_FISICA')
            ->select('CD_PESSOA_FISICA as id_externo', 'NM_PESSOA_FISICA as nome', 'NR_CPF as cpf')
            ->where('NR_CPF', $cpfLimpo)
            ->whereDate('DT_NASCIMENTO', $nascimento)
            ->first();
    }

    // -------------------------------------------------------------------------
    // BUSCAR AGENDAMENTOS
    // -------------------------------------------------------------------------
    public function buscarAgendamentos($idPaciente)
    {
        // 1. Busca usando Query Builder + Raw para funções do Oracle
        $agendamentos = DB::connection($this->connection)
            ->table('AGENDA_CONSULTA')
            ->select(
                'NR_SEQUENCIA as id',
                // Função Tasy para nome da agenda
                DB::raw("OBTER_DESC_AGENDA(CD_AGENDA) as agenda"), 
                'DT_AGENDA as data_agendamento',
                'IE_STATUS_AGENDA as status_codigo'
            )
            ->where('CD_PESSOA_FISICA', $idPaciente)
            // IMPORTANTE: Usa o SYSDATE do banco para ignorar fuso horário do PHP
            ->whereRaw("DT_AGENDA >= TRUNC(SYSDATE) - 30") 
            ->orderBy('DT_AGENDA', 'asc')
            ->get();

        // 2. Mapeamento de Status (Tasy -> Portal)
        return $agendamentos->map(function($agenda) {
            
            $statusAmigavel = match($agenda->status_codigo) {
                'O', 'IN', 'IPP', 'KP', 'PH', 'PS', 'M', 'N', 'PC' => 'confirmado',
                'A', 'PA', 'PF', 'PO', 'PR', 'R', 'RE', 'RN', 'RV' => 'agendado',
                'S', 'I', 'II', 'IT', 'H', 'C' => 'cancelado',
                'RG', 'E' => 'realizado',
                default => 'agendado'
            };

            return (object) [
                'id' => $agenda->id,
                'medico' => $agenda->agenda, 
                'especialidade' => 'Ambulatório', 
                'data_agendamento' => \Carbon\Carbon::parse($agenda->data_agendamento),
                'status' => $statusAmigavel,
                'tenant_id' => 'tasy_externo',
                'user_id' => null
            ];
        });
    }

    // -------------------------------------------------------------------------
    // BUSCAR EXAMES (RESULTADOS) - QUERY CORRIGIDA
    // -------------------------------------------------------------------------
    public function buscarExames($idPaciente)
    {
        // Query com DT_PROCEDIMENTO conforme solicitado
        $exames = DB::connection($this->connection)
            ->table('PROCEDIMENTO_PACIENTE as a')
            ->leftJoin('LAUDO_PACIENTE as b', 'a.NR_LAUDO', '=', 'b.NR_SEQUENCIA')
            ->select(
                'a.NR_SEQUENCIA as id_exame',
                DB::raw("OBTER_DESC_PROCEDIMENTO(a.CD_PROCEDIMENTO, '') as descricao"), 
                'b.DT_LIBERACAO as data_liberacao',
                DB::raw("CASE WHEN b.DT_LIBERACAO IS NULL THEN 'EA' ELSE 'LL' END AS status_laudo"),
                'a.NR_LAUDO',
                'a.DT_PROCEDIMENTO as data' // <--- Campo corrigido
            )
            // Filtro por função no WHERE
            ->whereRaw("OBTER_PF_ATENDIMENTO(a.NR_ATENDIMENTO, 'C') = ?", [$idPaciente])
            ->orderBy('a.DT_PROCEDIMENTO', 'desc') // Ordena pela data do procedimento
            ->limit(50)
            ->get();

        // Mapeamento
        return $exames->map(function($exame) {
            $statusFinal = ($exame->status_laudo === 'LL') ? 'LIBERADO' : 'EM ANÁLISE';
            
            // Usamos a 'data' (DT_PROCEDIMENTO) vinda da query
            $dataExibicao = $exame->data; 

            return (object) [
                'id_exame' => $exame->id_exame,
                'descricao' => $exame->descricao,
                'data' => $dataExibicao,
                'status' => $statusFinal,
                'status_laudo' => $exame->status_laudo // Passamos o raw também por garantia
            ];
        });
    }

    public function obterPdfLaudo($idExame)
    {
        // 1. Descobrir o NR_LAUDO
        $procedimento = DB::connection($this->connection)
            ->table('PROCEDIMENTO_PACIENTE')
            ->select('NR_LAUDO')
            ->where('NR_SEQUENCIA', $idExame)
            ->first();

        if (!$procedimento || !$procedimento->nr_laudo) {
            return null;
        }

        // 2. Buscar o HTML na coluna DS_LAUDO
        $laudo = DB::connection($this->connection)
            ->table('LAUDO_PACIENTE')
            ->select('DS_LAUDO', 'DS_TITULO_LAUDO')
            ->where('NR_SEQUENCIA', $procedimento->nr_laudo)
            ->first();

        if ($laudo && !empty($laudo->ds_laudo)) {
            // Retornamos um array estruturado para o Controller saber o que fazer
            return [
                'tipo' => 'html',
                'conteudo' => $laudo->ds_laudo,
                'titulo' => $laudo->ds_titulo_laudo ?? 'Resultado de Exame'
            ];
        }

        return null;
    }

    public function validarPaciente($cpf, $dataNascimento)
    {
        // Limpa o CPF (deixa só números)
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);

        // Busca na tabela PESSOA_FISICA (Tabela mestre de pacientes do Tasy)
        $paciente = DB::connection($this->connection)
            ->table('PESSOA_FISICA')
            ->select('CD_PESSOA_FISICA', 'NM_PESSOA_FISICA')
            ->where('NR_CPF', $cpfLimpo)
            ->whereRaw("TRUNC(DT_NASCIMENTO) = TO_DATE(?, 'YYYY-MM-DD')", [$dataNascimento])
            ->first();

        return $paciente;
    }

    // NOVO MÉTODO COM SEU SQL CORRIGIDO
    public function buscarUltimoConvenio($idPaciente)
    {
        try {
            $dados = DB::connection($this->connection)
                ->table('ATENDIMENTO_PACIENTE as a')
                ->join('ATEND_CATEGORIA_CONVENIO as c', 'a.NR_ATENDIMENTO', '=', 'c.NR_ATENDIMENTO')
                ->select(
                    // Função Tasy para nome do convênio
                    DB::raw("OBTER_DESC_CONVENIO(c.CD_CONVENIO) as convenio"),
                    // Carteirinha
                    'c.CD_USUARIO_CONVENIO as carteirinha'
                )
                ->where('a.CD_PESSOA_FISICA', $idPaciente)
                ->orderBy('a.DT_ENTRADA', 'desc')
                ->first();

            return $dados ?? (object) [
                'convenio' => 'Não identificado', 
                'carteirinha' => '---'
            ];

        } catch (\Exception $e) {
            // Em produção, você pode descomentar para ver o erro no log:
            // \Illuminate\Support\Facades\Log::error("Erro Convenio: " . $e->getMessage());
            
            return (object) [
                'convenio' => 'Erro na Busca', 
                'carteirinha' => '---'
            ];
        }
    }

    // NOVO MÉTODO DE PERFIL (COM SEU SQL)
    public function buscarDetalhesPaciente($idPaciente)
    {
        $sql = "
            SELECT *
            FROM (
                SELECT
                    a.NM_PESSOA_FISICA AS nome,
                    a.NR_CPF AS cpf,
                    a.NR_CARTAO_NAC_SUS AS cns,
                    OBTER_NOME_MAE_PF(a.cd_pessoa_fisica) AS nome_mae,
                    a.DT_NASCIMENTO AS nascimento,
                    b.DS_ENDERECO AS endereco,
                    b.NR_ENDERECO AS numero,
                    b.DS_BAIRRO AS bairro,
                    b.DS_MUNICIPIO AS cidade,
                    b.SG_ESTADO AS uf,
                    b.CD_CEP AS cep,
                    b.NR_TELEFONE AS celular,
                    b.DS_EMAIL AS email_tasy,
                    ROW_NUMBER() OVER (
                        PARTITION BY a.cd_pessoa_fisica
                        ORDER BY b.DT_ATUALIZACAO DESC
                    ) rn
                FROM PESSOA_FISICA a
                JOIN COMPL_PESSOA_FISICA b
                  ON a.cd_pessoa_fisica = b.cd_pessoa_fisica
                WHERE a.CD_PESSOA_FISICA = :id_paciente
            )
            WHERE rn = 1
        ";

        try {
            // Executa o SQL bruto passando o ID com segurança
            $dados = DB::connection($this->connection)->selectOne($sql, ['id_paciente' => $idPaciente]);

            return $dados;

        } catch (\Exception $e) {
            // Em caso de erro, retorna null para não quebrar a tela
            return null;
        }
    }
}