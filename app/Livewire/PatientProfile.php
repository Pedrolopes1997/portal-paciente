<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Services\Drivers\HealthSystemInterface;

class PatientProfile extends Component
{
    public $tenantSlug;
    
    public $email;
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount($tenant_slug)
    {
        $this->tenantSlug = $tenant_slug;
        $this->email = Auth::user()->email;
    }

    // --- AQUI ESTÁ A MÁGICA ---
    // Este método diz ao Livewire exatamente o que mostrar enquanto carrega
    public function placeholder()
    {
        return view('livewire.placeholders.skeleton-profile');
    }
    // ---------------------------

    public function updateProfile()
    {
        $user = Auth::user();
        $validated = $this->validate([
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('success', 'Dados de acesso atualizados com sucesso!');
    }

    public function render()
    {
        // Simulação de delay para você VER o skeleton funcionando (Remova em produção se quiser)
        // sleep(1); 

        $user = Auth::user();
        $tenant = Tenant::where('slug', $this->tenantSlug)->firstOrFail();

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

        if ($tenant->erp_driver === 'tasy') {
            $driver = new \App\Services\Drivers\TasyDriver($tenant);
            $idBusca = $user->tasy_cd_pessoa_fisica;
        } else {
            $driver = new \App\Services\Drivers\LocalDriver($tenant);
            $idBusca = $user->id;
        }

        $dadosCadastrais = null;
        try {
            if ($idBusca) {
                $dadosCadastrais = $driver->buscarDetalhesPaciente($idBusca);
            }
        } catch (\Exception $e) { }

        return view('livewire.patient-profile', [
            'user' => $user,
            'dadosCadastrais' => $dadosCadastrais,
            'tenant' => $tenant
        ]);
    }
}