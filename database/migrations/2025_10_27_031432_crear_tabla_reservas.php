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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id(); // ID de la reserva
            $table->unsignedBigInteger('usuario_id'); // Relación con el usuario que hizo la reserva
            $table->unsignedBigInteger('ruta_id'); // Relación con la ruta reservada
            $table->string('asiento'); // Asiento reservado
            $table->timestamps(); // Timestamps (created_at, updated_at)

            // Claves foráneas
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ruta_id')->references('id')->on('rutas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
