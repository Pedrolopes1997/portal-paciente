<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Estamos definindo o nome da classe explicitamente para o Laravel não se perder
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Adiciona apenas os campos novos
            
            if (!Schema::hasColumn('appointments', 'integration_source')) {
                $table->string('integration_source')->default('local')->index()->after('updated_at');
            }
            
            if (!Schema::hasColumn('appointments', 'integration_remote_id')) {
                $table->string('integration_remote_id')->nullable()->index()->after('integration_source');
            }

            if (!Schema::hasColumn('appointments', 'integration_payload')) {
                $table->json('integration_payload')->nullable()->after('integration_remote_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'integration_source')) {
                $table->dropColumn(['integration_source', 'integration_remote_id', 'integration_payload']);
            }
        });
    }
};