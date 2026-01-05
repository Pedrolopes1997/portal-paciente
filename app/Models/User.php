<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants; // <--- OBRIGATÓRIO PARA MULTI-TENANCY
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      // admin, user, etc
        'tenant_id', // Vínculo com a clínica
        'cpf',
        'tasy_cd_pessoa_fisica'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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

    // --- REGRAS DO FILAMENT (CRUCIAIS) ---

    /**
     * 1. LISTA DE CLÍNICAS DISPONÍVEIS
     */
    public function getTenants(Panel $panel): Collection
    {
        // Se for Super Admin, retorna TODAS as clínicas do sistema
        if ($this->role === 'super_admin') {
            return \App\Models\Tenant::all();
        }

        // Se for usuário comum, retorna só a dele
        if ($this->tenant) {
            return collect([$this->tenant]);
        }
        
        return collect();
    }

    /**
     * 2. PERMISSÃO DE ENTRADA NA CLÍNICA
     */
    public function canAccessTenant(Model $tenant): bool
    {
        // Super Admin tem chave mestra: entra em qualquer lugar
        if ($this->role === 'super_admin') {
            return true;
        }

        // Usuário comum só entra na clínica vinculada ao seu ID
        return $this->tenant_id === $tenant->id;
    }

    /**
     * 3. A Porta de Entrada (Painel Admin vs Painel Clínica)
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // --- ACESSO AO SUPER ADMIN (/admin) ---
        if ($panel->getId() === 'admin') {
            // Permite se tiver a role 'super_admin' OU se for o seu e-mail específico (fallback)
            return $this->role === 'super_admin' || $this->email === 'pedroarcangelo1997@gmail.com';
        }

        // --- ACESSO AO PAINEL DA CLÍNICA (/painel) ---
        if ($panel->getId() === 'painel') {
            // Só entra se tiver um tenant vinculado no banco
            return $this->tenant_id !== null;
        }

        return false;
    }
    
    // Configura o nome do usuário no topo do Filament
    public function getFilamentName(): string
    {
        return "{$this->name}";
    }

    public function sendPasswordResetNotification($token)
    {
        // Pega o slug do tenant associado a este usuário
        // Se a relação não estiver carregada, tenta pegar da rota ou do tenant_id
        $slug = $this->tenant ? $this->tenant->slug : request()->route('tenant_slug');
        
        $this->notify(new \App\Notifications\TenantResetPassword($token, $slug));
    }
}