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
    // BUSCAR EXAMES (RESULTADOS)
    // -------------------------------------------------------------------------
    public function buscarExames($idPaciente)
    {
        // Query com JOIN e CASE ajustados
        $exames = DB::connection($this->connection)
            ->table('PROCEDIMENTO_PACIENTE as a')
            ->leftJoin('LAUDO_PACIENTE as b', 'a.NR_LAUDO', '=', 'b.NR_SEQUENCIA')
            ->select(
                'a.NR_SEQUENCIA as id_exame',
                DB::raw("OBTER_DESC_PROCEDIMENTO(a.CD_PROCEDIMENTO, '') as descricao"), 
                'b.DT_LIBERACAO as data_liberacao',
                'b.DT_EXAME as data_realizado',
                DB::raw("CASE WHEN b.DT_LIBERACAO IS NULL THEN 'EA' ELSE 'LL' END AS status_laudo"),
                'a.NR_LAUDO'
            )
            // Filtro por função no WHERE
            ->whereRaw("OBTER_PF_ATENDIMENTO(a.NR_ATENDIMENTO, 'C') = ?", [$idPaciente])
            ->orderByRaw("b.DT_EXAME DESC NULLS LAST")
            ->limit(50)
            ->get();

        // Mapeamento
        return $exames->map(function($exame) {
            $statusFinal = ($exame->status_laudo === 'LL') ? 'LIBERADO' : 'EM ANÁLISE';
            $dataExibicao = $exame->data_realizado ?? $exame->data_liberacao;

            return (object) [
                'id_exame' => $exame->id_exame,
                'descricao' => $exame->descricao,
                'data' => $dataExibicao,
                'status' => $statusFinal
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
        // Nota: Ajuste os nomes das colunas conforme seu Tasy (geralmente é assim)
        $paciente = DB::connection($this->connection)
            ->table('PESSOA_FISICA')
            ->select('CD_PESSOA_FISICA', 'NM_PESSOA_FISICA')
            ->where('NR_CPF', $cpfLimpo)
            ->whereRaw("TRUNC(DT_NASCIMENTO) = TO_DATE(?, 'YYYY-MM-DD')", [$dataNascimento])
            ->first();

        return $paciente; // Retorna null se não achar, ou o objeto se achar
    }

}