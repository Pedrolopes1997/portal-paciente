<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime:H:i', // Importante para formatar input
        'end_time' => 'datetime:H:i',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}