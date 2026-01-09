<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialty extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'type' // <--- ADICIONE ISTO AQUI
    ];

    public function doctors()
    {
        return $this->belongsToMany(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}