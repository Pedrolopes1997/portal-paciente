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
    Schema::create('exams', function (Blueprint $table) {
        $table->id();
        
        // Vínculos obrigatórios do SaaS
        $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // O Paciente
        
        // Dados do Exame
        $table->string('title'); // Nome do exame (ex: Hemograma)
        $table->date('date');
        $table->string('status')->default('analise'); // analise, liberado
        $table->string('file_path')->nullable(); // Onde está o PDF salvo no servidor
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
