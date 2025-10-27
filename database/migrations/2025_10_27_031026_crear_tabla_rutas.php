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
        Schema::create('rutas', function (Blueprint $table) {
            $table->id(); // ID de la ruta
            $table->string('origen'); // Ciudad de origen
            $table->string('destino'); // Ciudad de destino
            $table->date('fecha'); // Fecha del viaje
            $table->time('hora_salida'); // Hora de salida
            $table->time('hora_llegada'); // Hora de llegada
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
