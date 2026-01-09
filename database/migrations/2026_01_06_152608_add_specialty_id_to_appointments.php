<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'specialty_id')) {
                // Adiciona a coluna de especialidade
                $table->foreignId('specialty_id')
                      ->nullable()
                      ->after('doctor_id')
                      ->constrained('specialties')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'specialty_id')) {
                $table->dropForeign(['specialty_id']);
                $table->dropColumn('specialty_id');
            }
        });
    }
};
