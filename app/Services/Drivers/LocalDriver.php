<?php

namespace App\Services\Drivers;

use App\Models\Exam;
use App\Models\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class LocalDriver implements HealthSystemInterface
{
    protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    // Método já existente (mantido)
    public function buscarExames(string $idPaciente): Collection
    {
        // No modo local, o idPaciente é o ID do usuário (Inteiro)
        return Exam::where('tenant_id', $this->tenant->id)
            ->where('user_id', $idPaciente)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($exame) {
                return (object) [
                    'id_exame'  => $exame->id,
                    'descricao' => $exame->title,
                    'data'      => $exame->date->format('Y-m-d'),
                    'status'    => $exame->status === 'liberado' ? 'LIB' : 'PEN',
                    'url_pdf'   => $exame->file_path, 
                ];
            });
    }

    // --- MÉTODOS OBRIGATÓRIOS DO CONTRATO (NOVOS) ---

    // Adicionamos ", string $nascimento" para respeitar o contrato
    public function buscarPaciente(string $cpf, string $nascimento): ?object
    {
        // No futuro, aqui buscaremos na tabela 'users' local se quisermos validar cadastro.
        // Por enquanto retornamos null.
        return null;
    }

    public function buscarAgendamentos($idPaciente)
    {
        return \App\Models\Appointment::where('user_id', $idPaciente)
            // AQUI É A MUDANÇA: Usamos $this->tenant->id
            ->where('tenant_id', $this->tenant->id) 
            ->where('status', '!=', 'cancelado')
            ->orderBy('data_agendamento', 'asc')
            ->get();
    }

    public function obterPdfLaudo($idExame)
    {
        // 1. Busca o registro no banco
        $exame = Exam::find($idExame);

        // 2. Verifica se existe registro e se tem arquivo anexado
        if (!$exame || !$exame->file_path) {
            return null;
        }

        // 3. Monta o caminho absoluto (Caminho Físico do Servidor)
        // O Filament salva na pasta 'public' do storage por padrão.
        // A função storage_path('app/public/') aponta para /home/pedro/portal-paciente/storage/app/public/
        $caminhoCompleto = storage_path('app/public/' . $exame->file_path);

        // 4. Verifica se o arquivo existe fisicamente no disco
        if (file_exists($caminhoCompleto)) {
            return $caminhoCompleto;
        }

        // Se chegou aqui, o registro existe no banco, mas o arquivo sumiu da pasta
        return null;
    }

    /**
     * Valida se o paciente existe (Para driver Local, isso não é usado pelo Controller atual,
     * pois o cadastro é liberado, mas precisamos cumprir o contrato da Interface).
     */
    public function validarPaciente($cpf, $dataNascimento)
    {
        // Retornamos null ou true. Como a lógica do Controller para 'local'
        // não chama essa função (ela faz o cadastro direto), isso aqui é só
        // para o PHP não dar erro fatal.
        return null;
    }
}