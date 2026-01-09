<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Adiciona a coluna de notas do paciente se não existir
            if (!Schema::hasColumn('appointments', 'patient_notes')) {
                $table->text('patient_notes')->nullable()->after('status');
            }

            // Já previne o próximo erro: Adiciona motivo de cancelamento
            if (!Schema::hasColumn('appointments', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('patient_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['patient_notes', 'cancellation_reason']);
        });
    }
};