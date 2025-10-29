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
        // El nombre de la tabla debe ser 'reserva_asientos' (plural) 
        // para seguir la convención de Laravel con el modelo 'ReservaAsiento'.
        Schema::create('reserva_asientos', function (Blueprint $table) {
            $table->id(); // ¡Añadido! Es mejor tener un ID único.

            $table->foreignId('reserva_id')->constrained('reservas')->onDelete('cascade');
            $table->foreignId('asiento_id')->constrained('asientos'); // No borres el asiento, solo la reserva
            $table->foreignId('viaje_id')->constrained('viajes'); // ¡Añadido!

            // ¡¡AQUÍ ESTÁN LOS DATOS DEL PASAJERO QUE FALTABAN!!
            $table->decimal('precio', 8, 2);
            $table->string('pasajero_nombre');
            $table->string('pasajero_dni', 20);
            $table->integer('pasajero_edad');
            
            $table->timestamps(); // ¡Añadido!

            // Opcional: Clave única para evitar doble reserva del mismo asiento
            $table->unique(['viaje_id', 'asiento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_asientos'); // Actualizado a plural
    }
};