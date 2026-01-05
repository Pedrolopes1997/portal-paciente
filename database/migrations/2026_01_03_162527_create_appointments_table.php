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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        
        // Vínculo com a Clínica (Tenant)
        $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
        
        // Vínculo com o Paciente
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Dados do Agendamento
        $table->string('medico'); // Nome do médico (String simples por enquanto)
        $table->string('especialidade')->nullable(); // Ex: Cardiologia
        $table->dateTime('data_agendamento'); // Data e Hora
        
        // Status: Agendado, Confirmado, Cancelado, Realizado
        $table->string('status')->default('agendado');
        
        $table->text('observacoes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
