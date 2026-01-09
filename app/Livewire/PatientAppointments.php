<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use App\Services\Drivers\HealthSystemInterface;
use Carbon\Carbon;

class PatientAppointments extends Component
{
    public $tenantSlug;
    
    // NOVO: Controla a aba ativa pelo PHP (Padrão: 'proximos')
    public $activeTab = 'proximos'; 

    public function mount($tenant_slug)
    {
        $this->tenantSlug = $tenant_slug;
    }

    // Função para trocar de aba via clique
    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function placeholder()
    {
        return view('livewire.placeholders.skeleton-agenda');
    }

    public function render()
    {
        $tenant = Tenant::where('slug', $this->tenantSlug)->firstOrFail();
        $user = Auth::user();

        // 1. Configura Tasy
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

        // 2. Instancia Driver
        if ($tenant->erp_driver === 'tasy') {
            $driver = new \App\Services\Drivers\TasyDriver($tenant);
            $idBusca = $user->tasy_cd_pessoa_fisica;
        } else {
            $driver = new \App\Services\Drivers\LocalDriver($tenant);
            $idBusca = $user->id;
        }

        // 3. Busca TUDO
        try {
            $todosAgendamentos = $driver->buscarAgendamentos($idBusca, true); 
        } catch (\Exception $e) {
            $todosAgendamentos = collect([]);
        }

        // 4. Separa Futuro de Passado
        $agora = Carbon::now();

        $proximos = $todosAgendamentos->filter(function ($item) use ($agora) {
            return Carbon::parse($item->scheduled_at)->gte($agora->copy()->startOfDay());
        })->sortBy('scheduled_at');

        $historico = $todosAgendamentos->filter(function ($item) use ($agora) {
            return Carbon::parse($item->scheduled_at)->lt($agora->copy()->startOfDay());
        })->sortByDesc('scheduled_at');

        return view('livewire.patient-appointments', [
            'proximos' => $proximos,
            'historico' => $historico,
            'tenant' => $tenant
        ]);
    }
}