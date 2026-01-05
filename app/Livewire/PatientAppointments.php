<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Services\Drivers\HealthSystemInterface;

class PatientAppointments extends Component
{
    public $tenantSlug;

    public function mount($tenant_slug)
    {
        $this->tenantSlug = $tenant_slug;
    }

    public function placeholder()
    {
        return view('livewire.placeholders.skeleton-agenda');
    }

    public function render()
    {
        $tenant = Tenant::where('slug', $this->tenantSlug)->firstOrFail();
        $user = Auth::user();

        // 1. RECONECTA NO BANCO DO CLIENTE (Se for Tasy)
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

        // 3. Busca os Agendamentos
        try {
            $agendamentos = $driver->buscarAgendamentos($idBusca);
        } catch (\Exception $e) {
            $agendamentos = collect([]);
        }

        // Retornamos para a view junto com o $tenant (necessário para o botão do WhatsApp)
        return view('livewire.patient-appointments', [
            'agendamentos' => $agendamentos,
            'tenant' => $tenant
        ]);
    }
}