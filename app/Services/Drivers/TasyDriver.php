<?php

namespace App\Services\Drivers;

use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Carbon\Carbon;

class TasyDriver implements HealthSystemInterface
{
    protected $connection = 'tenant_erp';
    protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    // =========================================================================
    // PARTE 1: SEUS MÉTODOS ORIGINAIS
    // =========================================================================

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

    public function buscarAgendamentos($idPaciente)
    {
        // 1. Busca usando Query Builder
        $agendamentos = DB::connection($this->connection)
            ->table('AGENDA_CONSULTA')
            ->select(
                'NR_SEQUENCIA as id',
                DB::raw("OBTER_DESC_AGENDA(CD_AGENDA) as agenda"), 
                'DT_AGENDA as data_agendamento', // Mantive o nome original do banco aqui
                'IE_STATUS_AGENDA as status_codigo'
            )
            ->where('CD_PESSOA_FISICA', $idPaciente)
            ->whereRaw("DT_AGENDA >= TRUNC(SYSDATE) - 30") 
            ->orderBy('DT_AGENDA', 'asc')
            ->get();

        // 2. Mapeamento
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
                
                // --- IMPORTANTE: ADICIONADO PARA O DASHBOARD NÃO QUEBRAR ---
                'type' => 'consulta', 
                
                // Renomeia para o padrão novo que o dashboard espera
                'scheduled_at' => \Carbon\Carbon::parse($agenda->data_agendamento),
                
                'especialidade' => 'Ambulatório', 
                'status' => $statusAmigavel,
                'tenant_id' => 'tasy_externo',
                'user_id' => null
            ];
        });
    }

    public function buscarExames($idPaciente)
    {
        $exames = DB::connection($this->connection)
            ->table('PROCEDIMENTO_PACIENTE as a')
            ->leftJoin('LAUDO_PACIENTE as b', 'a.NR_LAUDO', '=', 'b.NR_SEQUENCIA')
            ->select(
                'a.NR_SEQUENCIA as id_exame',
                DB::raw("OBTER_DESC_PROCEDIMENTO(a.CD_PROCEDIMENTO, '') as descricao"), 
                'b.DT_LIBERACAO as data_liberacao',
                DB::raw("CASE WHEN b.DT_LIBERACAO IS NULL THEN 'EA' ELSE 'LL' END AS status_laudo"),
                'a.NR_LAUDO',
                'a.DT_PROCEDIMENTO as data'
            )
            ->whereRaw("OBTER_PF_ATENDIMENTO(a.NR_ATENDIMENTO, 'C') = ?", [$idPaciente])
            ->orderBy('a.DT_PROCEDIMENTO', 'desc')
            ->limit(50)
            ->get();

        return $exames->map(function($exame) {
            $statusFinal = ($exame->status_laudo === 'LL') ? 'LIBERADO' : 'EM ANÁLISE';
            $dataExibicao = $exame->data; 

            return (object) [
                'id_exame' => $exame->id_exame,
                'descricao' => $exame->descricao,
                'data' => $dataExibicao,
                'status' => $statusFinal,
                'status_laudo' => $exame->status_laudo
            ];
        });
    }

    public function obterPdfLaudo($idExame)
    {
        $procedimento = DB::connection($this->connection)
            ->table('PROCEDIMENTO_PACIENTE')
            ->select('NR_LAUDO')
            ->where('NR_SEQUENCIA', $idExame)
            ->first();

        if (!$procedimento || !$procedimento->nr_laudo) {
            return null;
        }

        $laudo = DB::connection($this->connection)
            ->table('LAUDO_PACIENTE')
            ->select('DS_LAUDO', 'DS_TITULO_LAUDO')
            ->where('NR_SEQUENCIA', $procedimento->nr_laudo)
            ->first();

        if ($laudo && !empty($laudo->ds_laudo)) {
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
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);

        $paciente = DB::connection($this->connection)
            ->table('PESSOA_FISICA')
            ->select('CD_PESSOA_FISICA', 'NM_PESSOA_FISICA')
            ->where('NR_CPF', $cpfLimpo)
            ->whereRaw("TRUNC(DT_NASCIMENTO) = TO_DATE(?, 'YYYY-MM-DD')", [$dataNascimento])
            ->first();

        return $paciente;
    }

    public function buscarUltimoConvenio($idPaciente)
    {
        try {
            $dados = DB::connection($this->connection)
                ->table('ATENDIMENTO_PACIENTE as a')
                ->join('ATEND_CATEGORIA_CONVENIO as c', 'a.NR_ATENDIMENTO', '=', 'c.NR_ATENDIMENTO')
                ->select(
                    DB::raw("OBTER_DESC_CONVENIO(c.CD_CONVENIO) as convenio"),
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
            return (object) [
                'convenio' => 'Erro na Busca', 
                'carteirinha' => '---'
            ];
        }
    }

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
            $dados = DB::connection($this->connection)->selectOne($sql, ['id_paciente' => $idPaciente]);
            return $dados;
        } catch (\Exception $e) {
            return null;
        }
    }

    // =========================================================================
    // PARTE 2: NOVOS MÉTODOS DE MOCK (PARA AGENDAMENTO)
    // =========================================================================

    public function buscarEspecialidades(string $tipo = 'consulta')
    {
        if ($tipo === 'exame') {
            return collect([
                (object)['id' => 901, 'name' => 'Ultrassonografia (Tasy)', 'descricao' => 'Imagem'],
                (object)['id' => 902, 'name' => 'Raio-X (Tasy)', 'descricao' => 'Imagem']
            ]);
        }

        return collect([
            (object)['id' => 101, 'name' => 'Cardiologia (Tasy)', 'descricao' => 'Consulta'],
            (object)['id' => 102, 'name' => 'Ortopedia (Tasy)', 'descricao' => 'Consulta']
        ]);
    }

    // CORRIGIDO: Adicionado "= null" para compatibilidade com Interface
    public function buscarMedicos($idEspecialidade = null)
    {
        return [
            (object)[
                'id' => 999, 
                'name' => 'Dr. Tasy Teste', 
                'crm' => '12345-SP'
            ]
        ];
    }

    public function buscarHorariosDisponiveis($idMedico, string $data)
    {
        return [
            ['time' => '08:00', 'available' => true],
            ['time' => '10:30', 'available' => true],
            ['time' => '14:00', 'available' => true],
        ];
    }

    public function criarAgendamento(array $dados)
    {
        return (object) [
            'id' => rand(5000, 9999),
            'status' => 'confirmed',
            'scheduled_at' => $dados['scheduled_at'] ?? now()
        ];
    }

    public function checkPassword(string $cpf, string $password): bool
    {
        return true; 
    }
    
    public function buscarPacientePorCpf(string $cpf)
    {
         return null; 
    }
}