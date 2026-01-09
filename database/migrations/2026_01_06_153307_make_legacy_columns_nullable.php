<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Torna a coluna antiga 'medico' opcional
            if (Schema::hasColumn('appointments', 'medico')) {
                // string ou text ou integer? Geralmente medico antigo é string (nome) ou ID.
                // O ->change() mantém o tipo atual e só adiciona o nullable.
                $table->string('medico')->nullable()->change();
            }

            // Prevenção: Faz o mesmo para 'paciente' se existir (pois usamos patient_id agora)
            if (Schema::hasColumn('appointments', 'paciente')) {
                $table->string('paciente')->nullable()->change();
            }

            // Prevenção: Faz o mesmo para 'observacoes' se existir (pois usamos patient_notes agora)
            if (Schema::hasColumn('appointments', 'observacoes')) {
                $table->text('observacoes')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // Não precisamos reverter para "obrigatório", pois nullable não quebra nada.
    }
};