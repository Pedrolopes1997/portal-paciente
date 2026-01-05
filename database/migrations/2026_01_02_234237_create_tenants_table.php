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
    Schema::create('tenants', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nome da Clínica/Hospital
        $table->string('slug')->unique(); // Para subdominios (ex: clinica-x.seuportal.com)
        $table->string('logo_path')->nullable(); // Logo personalizada
        $table->string('primary_color')->default('#0ea5e9'); // Cor personalizada do cliente
        
        // TIPO DO PLANO
        // 'standalone' = Banco Local
        // 'integrated' = Conecta em ERP externo
        $table->enum('mode', ['standalone', 'integrated'])->default('standalone');
        
        // TIPO DE INTEGRAÇÃO (Só usado se mode == integrated)
        $table->enum('erp_driver', ['tasy', 'mv', 'protheus'])->nullable();
        
        // CONFIGURAÇÕES DO BANCO EXTERNO (Salvo Criptografado)
        // Guardaremos Host, User, Pass, ServiceName, Schema
        $table->text('db_connection_data')->nullable(); 
        
        $table->timestamps();
    });

    // Adicionar tenant_id na tabela users
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('tenant_id')->nullable()->constrained()->after('id');
        // Define se é admin da clinica ou paciente
        $table->enum('role', ['super_admin', 'admin_clinica', 'paciente'])->default('paciente')->after('email');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
