<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Services\Drivers\HealthSystemInterface;
use Carbon\Carbon;

class PatientExams extends Component
{
    public $tenantSlug;

    // Recebe o slug quando o componente é chamado na view
    public function mount($tenant_slug)
    {
        $this->tenantSlug = $tenant_slug;
    }

    // O Placeholder é o "Esqueleto" que aparece enquanto carrega
    public function placeholder()
    {
        return view('livewire.placeholders.skeleton-table');
    }

    public function render()
    {
        // 1. Recupera o Tenant
        $tenant = Tenant::where('slug', $this->tenantSlug)->firstOrFail();

        // 2. RECONECTA NO BANCO DO CLIENTE
        if ($tenant->mode === 'integrated' && !empty($tenant->db_connection_data)) {
            $dados = $tenant->db_connection_data;
            
            // Configura conexão Oracle/Tasy em tempo real
            if ($tenant->erp_driver === 'tasy') {
                
                // CORREÇÃO AQUI: Mudamos de 'oracle' para 'tenant_erp'
                // O TasyDriver espera encontrar uma conexão chamada 'tenant_erp'
                Config::set('database.connections.tenant_erp', [
                    'driver'        => 'oracle',
                    'tns'           => "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST={$dados['db_host']})(PORT={$dados['db_port']}))(CONNECT_DATA=(SERVICE_NAME={$dados['db_database']})))",
                    'host'          => $dados['db_host'],
                    'port'          => $dados['db_port'],
                    'database'      => $dados['db_database'],
                    'username'      => $dados['db_username'],
                    'password'      => $dados['db_password'],
                    'charset'       => 'AL32UTF8',
                    'prefix'        => '',
                ]);
            }
        }

        // 3. Instancia o Driver Correto
        if ($tenant->erp_driver === 'tasy') {
            $driver = new \App\Services\Drivers\TasyDriver($tenant);
        } else {
            $driver = new \App\Services\Drivers\LocalDriver($tenant);
        }

        // 4. Busca os dados (Isso pode demorar, por isso usamos Lazy Loading)
        $idBusca = $this->getPacienteId(Auth::user(), $tenant);
        
        try {
            $examesRaw = $driver->buscarExames($idBusca);
        } catch (\Exception $e) {
            // Se der erro na conexão, retornamos vazio para não quebrar a tela inteira
            // Em produção, você pode logar esse erro: Log::error($e->getMessage());
            $examesRaw = collect([]);
        }
        
        // 5. Formata os dados
        $exames = $this->formatarExames($examesRaw, $tenant);

        return view('livewire.patient-exams', [
            'exames' => $exames
        ]);
    }

    // Helpers
    private function getPacienteId($user, $tenant)
    {
        if ($tenant->erp_driver === 'tasy') {
             return $user->tasy_cd_pessoa_fisica;
        }
        return $user->id;
    }

    private function formatarExames($examesRaw, $tenant)
    {
        return $examesRaw->map(function($exame) use ($tenant) {
            $exameObj = (object) $exame;
            
            $status = strtoupper($exameObj->status_laudo ?? $exameObj->status ?? '');
            
            // Lista de status considerados "Liberado"
            $isLiberado = in_array($status, ['L', 'LIB', 'LIBERADO', 'PUBLICADO', 'LL']); // LL = Laudo Liberado (Tasy comum)

            $link = '#';
            if ($isLiberado) {
                $link = route('paciente.exames.visualizar', [
                    'tenant_slug' => $tenant->slug, 
                    'id' => $exameObj->id_exame ?? 0
                ]);
            }

            return [
                'id_pdf'      => $exameObj->id_exame ?? 0, 
                'descricao'   => $exameObj->descricao ?? 'Exame',
                'data'        => isset($exameObj->data_realizado) ? Carbon::parse($exameObj->data_realizado)->format('d/m/Y') : (isset($exameObj->data) ? Carbon::parse($exameObj->data)->format('d/m/Y') : '--'),
                'status'      => $isLiberado ? 'Liberado' : 'Em Análise',
                'link_laudo'  => $link,
                'url_pdf'     => $exameObj->url_pdf ?? null
            ];
        });
    }
}