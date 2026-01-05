<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'title',
        'date',
        'status',
        'file_path',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Exame pertence a uma Clínica
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Exame pertence a um Paciente (User)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}