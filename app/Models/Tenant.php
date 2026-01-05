<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany; // <--- Importante

class Tenant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'whatsapp',
        'domain',
        'logo_path',
        'primary_color',
        'mode',           // 'standalone' ou 'integrated'
        'erp_driver',     // 'tasy', 'mv'
        'db_connection_data', // JSON com as credenciais
        'subscription_start',
        'subscription_end',
    ];

    protected $casts = [
        'db_connection_data' => 'encrypted:array',
        'is_active' => 'boolean',
        // --- ADICIONE AQUI TAMBÉM (Opcional, mas recomendado) ---
        'subscription_start' => 'date',
        'subscription_end' => 'date',
        // --------------------------------------------------------
    ];
    
        public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    // Relacionamento: Uma clínica tem vários usuários
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relacionamento: Uma Clínica tem muitos Agendamentos.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}