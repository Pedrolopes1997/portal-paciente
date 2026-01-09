<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Cria a flag que permite ser paciente junto com qualquer outro cargo
        $table->boolean('is_patient')->default(false)->after('role');
    });

    // --- MIGRAR DADOS ANTIGOS AGORA MESMO ---
    // Pega todo mundo que já era paciente (em inglês ou português) e marca a flag
    \DB::table('users')
        ->whereIn('role', ['patient', 'paciente', 'Patient'])
        ->update(['is_patient' => true, 'role' => 'patient']); 
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
