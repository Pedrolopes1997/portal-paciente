<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\TenantScope; // Se você estiver usando Scope Global (opcional)
use Illuminate\Support\Facades\Auth;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data_agendamento' => 'datetime',
    ];

    // --- RELACIONAMENTOS ---

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user() // O Paciente
    {
        return $this->belongsTo(User::class);
    }

    // --- AUTO-PREENCHIMENTO DO TENANT ---
    // Sempre que criar um agendamento, ele pega o tenant do usuário logado (Médico/Admin)
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check() && !$model->tenant_id) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
        
        // Opcional: Se você usa TenantScope global para filtrar queries
        // static::addGlobalScope(new TenantScope);
    }
}