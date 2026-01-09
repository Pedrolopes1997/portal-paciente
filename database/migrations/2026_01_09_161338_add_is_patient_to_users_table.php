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
    // 1. CORREÇÃO DO ERRO: Alterar a coluna 'role' para VARCHAR
    // Isso resolve o "Data truncated". Usamos SQL direto para ser compatível e rápido.
    \DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NULL DEFAULT NULL");

    // 2. CRIAR A COLUNA (Com verificação de segurança)
    // Como sua migração falhou no meio, verificamos se a coluna já existe antes de criar
    if (!Schema::hasColumn('users', 'is_patient')) {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_patient')->default(false)->after('role');
        });
    }

    // 3. ATUALIZAR OS DADOS
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
