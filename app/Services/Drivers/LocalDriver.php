<?php

namespace App\Services\Drivers;

use App\Models\Exam;
use App\Models\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class LocalDriver implements HealthSystemInterface
{
    protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    // AJUSTE 1: Removemos "string" e ": Collection" para ficar compatível com a Interface
    public function buscarExames($idPaciente)
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
                    // Adicionamos status_laudo para compatibilidade com o Dashboard novo
                    'status_laudo' => $exame->status === 'liberado' ? 'LL' : 'EA', 
                    'url_pdf'   => $exame->file_path, 
                ];
            });
    }

    // AJUSTE 2: Adicionamos este método que agora é obrigatório na Interface
    public function buscarUltimoConvenio($idPaciente)
    {
        return (object) [
            'convenio' => 'Particular (Local)',
            'carteirinha' => '0000.0000.0000'
        ];
    }

    // --- MÉTODOS ORIGINAIS MANTIDOS ABAIXO ---

    public function buscarPaciente(string $cpf, string $nascimento)
    {
        return null;
    }

    public function buscarAgendamentos($idPaciente)
    {
        return \App\Models\Appointment::where('user_id', $idPaciente)
            ->where('tenant_id', $this->tenant->id) 
            ->where('status', '!=', 'cancelado')
            ->orderBy('data_agendamento', 'asc')
            ->get();
    }

    public function obterPdfLaudo($idExame)
    {
        $exame = Exam::find($idExame);

        if (!$exame || !$exame->file_path) {
            return null;
        }

        $caminhoCompleto = storage_path('app/public/' . $exame->file_path);

        if (file_exists($caminhoCompleto)) {
            return $caminhoCompleto;
        }

        return null;
    }

    public function validarPaciente($cpf, $dataNascimento)
    {
        return null;
    }

    public function buscarDetalhesPaciente($idPaciente)
    {
        // Busca o usuário REAL no banco
        $user = User::find($idPaciente);

        if (!$user) return null;

        // Retorna um objeto com a MESMA estrutura que o Tasy retorna
        return (object) [
            'nome' => $user->name,
            'cpf' => $user->cpf ?? '---',
            'cns' => $user->cns ?? '---',
            'nome_mae' => $user->nome_mae ?? '---',
            'nascimento' => $user->nascimento ? \Carbon\Carbon::parse($user->nascimento)->format('d/m/Y') : '--/--/--',
            'endereco' => $user->endereco,
            'numero' => $user->numero,
            'complemento' => $user->complemento,
            'bairro' => $user->bairro,
            'cidade' => $user->cidade,
            'uf' => $user->uf,
            'cep' => $user->cep,
            'celular' => $user->celular,
            'email_tasy' => $user->email // Email de cadastro
        ];
    }
}