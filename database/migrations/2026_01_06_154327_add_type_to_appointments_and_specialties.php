<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        // 1. Identificar se o Agendamento é 'consulta' ou 'exame'
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'type')) {
                $table->string('type')->default('consulta')->after('id'); // consulta, exame
            }
        });

        // 2. Classificar as Especialidades (Ex: 'Cardiologia' = consulta, 'Raio-X' = exame)
        Schema::table('specialties', function (Blueprint $table) {
            if (!Schema::hasColumn('specialties', 'type')) {
                $table->string('type')->default('consulta')->after('name'); 
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('specialties', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
