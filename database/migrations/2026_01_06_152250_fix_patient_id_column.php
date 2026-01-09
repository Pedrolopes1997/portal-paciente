<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Tenta achar o nome antigo comum e renomeia para o padrão novo
            if (Schema::hasColumn('appointments', 'user_id')) {
                $table->renameColumn('user_id', 'patient_id');
            } 
            elseif (Schema::hasColumn('appointments', 'paciente_id')) {
                $table->renameColumn('paciente_id', 'patient_id');
            }
            // Se não existir nenhuma, cria a nova
            elseif (!Schema::hasColumn('appointments', 'patient_id')) {
                $table->foreignId('patient_id')->after('tenant_id')->constrained('users')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'patient_id')) {
                $table->renameColumn('patient_id', 'user_id');
            }
        });
    }
};