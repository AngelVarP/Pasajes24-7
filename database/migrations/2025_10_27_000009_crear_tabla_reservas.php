<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // database/migrations/2025_10_27_031432_crear_tabla_reservas.php

    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viaje_id')->constrained('viajes');
            $table->foreignId('user_id')->nullable()->constrained('users'); // Opcional, si el usuario está logueado
            
            $table->string('codigo_reserva')->unique();
            $table->decimal('monto_total', 10, 2);
            $table->string('estado')->default('pendiente_pago'); // "pendiente_pago", "pagado", "cancelado"
            
            // Información del pasajero principal (quien compra)
            $table->string('nombre_comprador');
            $table->string('dni_comprador', 20);
            $table->string('email_comprador');
            $table->string('telefono_comprador')->nullable();

            $table->timestamps();
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
