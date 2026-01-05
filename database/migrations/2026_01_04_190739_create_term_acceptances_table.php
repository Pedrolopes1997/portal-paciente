<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('term_acceptances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('term_version', 10)->default('v1.0'); // Para saber qual versão ele aceitou
            $table->string('ip_address', 45); // IPv4 ou IPv6
            $table->text('user_agent')->nullable(); // Navegador/Dispositivo usado
            $table->timestamps(); // Guarda o created_at (Data do aceite)
        });
    }

    public function down()
    {
        Schema::dropIfExists('term_acceptances');
    }
};