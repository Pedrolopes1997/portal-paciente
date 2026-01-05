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
        // CD_PESSOA_FISICA ou Código do Paciente no Tasy
        $table->unsignedBigInteger('tasy_cd_pessoa_fisica')->nullable()->after('id');
        // CPF para validação
        $table->string('cpf', 14)->nullable()->unique()->after('email');
    	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['tasy_cd_pessoa_fisica', 'cpf']);
    	});
    }
};
