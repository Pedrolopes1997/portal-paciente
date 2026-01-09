<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // 1. Tenta renomear 'data_agendamento' para 'scheduled_at' (Preserva dados antigos)
            if (Schema::hasColumn('appointments', 'data_agendamento')) {
                $table->renameColumn('data_agendamento', 'scheduled_at');
            } 
            // 2. Se não tinha a antiga e nem a nova, cria do zero
            elseif (!Schema::hasColumn('appointments', 'scheduled_at')) {
                $table->dateTime('scheduled_at')->nullable()->after('patient_id');
            }

            // 3. Verifica se tem a coluna 'doctor_id' (caso sua tabela antiga usasse outro nome)
            if (!Schema::hasColumn('appointments', 'doctor_id')) {
                // Se você usava 'medico_id' ou 'user_id' para médico, precisaria renomear igual acima
                // Aqui vou assumir que precisa criar ou já existe
                $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Reverte a mudança se precisar
            if (Schema::hasColumn('appointments', 'scheduled_at')) {
                $table->renameColumn('scheduled_at', 'data_agendamento');
            }
        });
    }
};