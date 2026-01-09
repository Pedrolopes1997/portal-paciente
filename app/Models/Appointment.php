<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Importação necessária do Model Tenant
use App\Models\Tenant;

class Appointment extends Model
{
    // --- CONFIGURAÇÃO ---
    protected $table = 'appointments';

    protected $fillable = [
        'tenant_id',
        'type',             // <--- Adicionado para Consulta/Exame
        'patient_id',       // Padrão novo
        'doctor_id',        
        'medico',           // Legado (string)
        'specialty_id',     
        'scheduled_at',     
        'data_agendamento', // Legado (virtual)
        'status',
        'patient_notes',
        'observacoes',      // Legado (virtual)
        'cancellation_reason',
        'integration_source',
        'integration_remote_id',
        'integration_payload',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'data_agendamento' => 'datetime',
        'integration_payload' => 'array',
    ];

    // --- RELACIONAMENTOS ---

    // CORREÇÃO: Adicionado o relacionamento Tenant obrigatório para o Filament
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function patient(): BelongsTo
    {
        // Como renomeamos fisicamente no banco, a chave É patient_id
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    // --- CAMADA DE COMPATIBILIDADE (ACCESSORS) ---

    // 1. Se chamarem $appointment->medico
    public function getMedicoAttribute($value)
    {
        return $value ?? ($this->doctor ? $this->doctor->name : null);
    }

    // 2. Se chamarem $appointment->data_agendamento
    public function getDataAgendamentoAttribute($value)
    {
        return $this->scheduled_at ?? $value;
    }

    // 3. Se chamarem $appointment->observacoes
    public function getObservacoesAttribute($value)
    {
        return $this->patient_notes ?? $value;
    }
    
    // 4. Se chamarem $appointment->user_id
    public function getUserIdAttribute($value)
    {
        return $this->patient_id;
    }

    // --- EVENTOS DE MODEL (SYNC AUTOMÁTICO) ---
    protected static function booted()
    {
        static::saving(function ($appointment) {
            // Sincroniza Data (Virtualmente)
            if ($appointment->scheduled_at && !$appointment->data_agendamento) {
                $appointment->setAttribute('data_agendamento', $appointment->scheduled_at);
            }
        });
    }

    // Compatibilidade extra
    public function getEspecialidadeAttribute($value)
    {
        return $this->specialty ? $this->specialty->name : $value;
    }
}