<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Services\Drivers\HealthSystemInterface;
use Carbon\Carbon;

class PatientDashboard extends Component
{
    public $tenantSlug;

    public function mount($tenant_slug)
    {
        $this->tenantSlug = $tenant_slug;
    }

    public function placeholder()
    {
        return view('livewire.placeholders.skeleton-dashboard');
    }

    public function render()
    {
        $user = Auth::user();
        $tenant = Tenant::where('slug', $this->tenantSlug)->firstOrFail();

        // 1. Conexão Tasy (se necessário)
        if ($tenant->mode === 'integrated' && !empty($tenant->db_connection_data) && $tenant->erp_driver === 'tasy') {
            $dados = $tenant->db_connection_data;
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

        // 2. Instancia o Driver
        if ($tenant->erp_driver === 'tasy') {
            $driver = new \App\Services\Drivers\TasyDriver($tenant);
            $idBusca = $user->tasy_cd_pessoa_fisica;
        } else {
            $driver = new \App\Services\Drivers\LocalDriver($tenant);
            $idBusca = $user->id;
        }

        // 3. Busca os Dados
        try {
            $agendamentos = $driver->buscarAgendamentos($idBusca)->take(3);
            
            // BUSCA ORIGINAL (Traz até 50 exames conforme o driver)
            $examesRaw = $driver->buscarExames($idBusca);
            
            // CONTA REAL (Antes de cortar para 5)
            $totalExamesCount = $examesRaw->count(); 
            
            // FORMATA E CORTA (Para a lista visual)
            $exames = $this->formatarExames($examesRaw, $tenant)->take(5);

            // NOVO: Busca dados reais do convênio
            // Se for LocalDriver, precisamos criar um método fake ou tratar aqui
            if ($tenant->erp_driver === 'tasy') {
                $dadosConvenio = $driver->buscarUltimoConvenio($idBusca);
            } else {
                $dadosConvenio = (object) ['convenio' => 'Local / Teste', 'carteirinha' => '0000'];
            }
            

        } catch (\Exception $e) {
            $agendamentos = collect([]);
            $exames = collect([]);
            $totalExamesCount = 0;
            $dadosConvenio = (object) ['convenio' => '--', 'carteirinha' => '--'];
        }

        $paciente = [
            'nome' => $user->name,
            'carteirinha' => $dadosConvenio->carteirinha, // Agora é real
            'plano' => $dadosConvenio->convenio           // Agora é real
        ];

        return view('livewire.patient-dashboard', [
            'paciente' => $paciente,
            'agendamentos' => $agendamentos,
            'exames' => $exames,
            'totalExamesCount' => $totalExamesCount, // Passamos a contagem correta
            'tenant' => $tenant,
            'user' => $user
        ]);
    }

    private function formatarExames($examesRaw, $tenant)
    {
        return $examesRaw->map(function($exame) use ($tenant) {
            $exameObj = (object) $exame;
            $status = strtoupper($exameObj->status_laudo ?? $exameObj->status ?? '');
            $isLiberado = in_array($status, ['L', 'LIB', 'LIBERADO', 'PUBLICADO', 'LL']);

            // Tratamento Data
            $dataTexto = '--';
            if (isset($exameObj->data_realizado) && $exameObj->data_realizado) {
                $dataTexto = Carbon::parse($exameObj->data_realizado)->format('d/m/Y');
            } elseif (isset($exameObj->data) && $exameObj->data) {
                $dataTexto = Carbon::parse($exameObj->data)->format('d/m/Y');
            } else {
                $dataTexto = 'Data pendente';
            }

            // Tratamento Status
            $statusDescritivo = $isLiberado ? 'Disponível' : 'Em análise';
            if ($status === 'EA') $statusDescritivo = 'Em análise';
            if ($status === 'P') $statusDescritivo = 'Pendente';

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
                'data'        => $dataTexto,
                'status'      => $statusDescritivo,
                'is_liberado' => $isLiberado,
                'link_laudo'  => $link,
            ];
        });
    }
}