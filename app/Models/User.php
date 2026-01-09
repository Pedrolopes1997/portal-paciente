<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants; 
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_patient',
        'tenant_id',
        'cpf',
        'crm',
        'tasy_cd_pessoa_fisica',
        'cns',
        'nome_mae',
        'nascimento',
        'celular',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        // --- NOVOS CAMPOS DE CONVÊNIO ---
        'convenio',
        'carteirinha',
        'validade_carteirinha',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'nascimento' => 'date',
            'validade_carteirinha' => 'date', 
            'is_patient' => 'boolean',
        ];
    }

    // --- RELACIONAMENTOS ---

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class);
    }

    // --- REGRAS DO FILAMENT (MULTI-TENANCY) ---

    public function getTenants(Panel $panel): Collection
    {
        // REGRA DE OURO: Se for Super Admin, retorna TODAS as clínicas do banco.
        // Isso faz aparecer o menu de troca de clínica no topo do painel.
        if ($this->role === 'super_admin') {
            return \App\Models\Tenant::all();
        }

        // Usuários normais só veem a clínica onde estão cadastrados
        if ($this->tenant) {
            return collect([$this->tenant]);
        }
        
        return collect();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        // Super Admin pode acessar qualquer Tenant
        if ($this->role === 'super_admin') {
            return true;
        }

        // Usuários normais só acessam seu próprio tenant
        return $this->tenant_id === $tenant->id;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // 1. Acesso ao Painel Central (admin global)
        if ($panel->getId() === 'admin') {
            return $this->role === 'super_admin' || $this->email === 'pedroarcangelo1997@gmail.com';
        }

        // 2. Acesso ao Painel da Clínica (painel)
        if ($panel->getId() === 'painel') {
            // Se for Super Admin, entra sempre (mesmo se tenant_id for null)
            if ($this->role === 'super_admin') {
                return true;
            }

            // Usuários normais precisam ter um tenant vinculado
            return $this->tenant_id !== null;
        }

        return false;
    }
    
    public function getFilamentName(): string
    {
        return "{$this->name}";
    }

    public function sendPasswordResetNotification($token)
    {
        // Tenta pegar o slug do tenant atual, ou da rota, ou deixa null
        $slug = null;
        
        if ($this->tenant) {
            $slug = $this->tenant->slug;
        } elseif (request()->route('tenant_slug')) {
            $slug = request()->route('tenant_slug');
        }

        $this->notify(new \App\Notifications\TenantResetPassword($token, $slug));
    }
}