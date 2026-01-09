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
use Illuminate\Database\Eloquent\Relations\HasMany; // Importante para o schedule

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
        // --------------------------------
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

    // --- CORREÇÃO DO ERRO 500 ---
    // Adicionamos o relacionamento com as Agendas (Schedules)
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
    // ----------------------------

    // Um médico tem várias especialidades
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class);
    }

    // --- REGRAS DO FILAMENT ---

    public function getTenants(Panel $panel): Collection
    {
        if ($this->role === 'super_admin') {
            return \App\Models\Tenant::all();
        }

        if ($this->tenant) {
            return collect([$this->tenant]);
        }
        
        return collect();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        return $this->tenant_id === $tenant->id;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'super_admin' || $this->email === 'pedroarcangelo1997@gmail.com';
        }

        if ($panel->getId() === 'painel') {
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
        $slug = $this->tenant ? $this->tenant->slug : request()->route('tenant_slug');
        $this->notify(new \App\Notifications\TenantResetPassword($token, $slug));
    }
}