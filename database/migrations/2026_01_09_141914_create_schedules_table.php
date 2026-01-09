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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tenant_id')->constrained();
        $table->foreignId('user_id')->constrained()->label('Médico'); // O médico dono da agenda
        
        // 0 = Domingo, 1 = Segunda, etc.
        $table->integer('day_of_week'); 
        
        $table->time('start_time'); // 08:00
        $table->time('end_time');   // 18:00
        $table->integer('duration_minutes')->default(30); // Tempo de cada consulta
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
