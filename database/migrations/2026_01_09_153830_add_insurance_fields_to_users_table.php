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
        $table->string('convenio')->nullable()->after('celular'); // Nome do Convênio
        $table->string('carteirinha')->nullable()->after('convenio'); // Número da carteirinha
        $table->date('validade_carteirinha')->nullable()->after('carteirinha'); // Opcional, mas útil
    });
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
