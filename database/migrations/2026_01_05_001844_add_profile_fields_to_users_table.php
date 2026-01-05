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
        $table->string('cns')->nullable();
        $table->string('nome_mae')->nullable();
        $table->date('nascimento')->nullable();
        $table->string('celular')->nullable();
        $table->string('cep')->nullable();
        $table->string('endereco')->nullable();
        $table->string('numero')->nullable();
        $table->string('bairro')->nullable();
        $table->string('cidade')->nullable();
        $table->string('uf', 2)->nullable();
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
