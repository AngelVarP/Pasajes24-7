<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    

    // database/migrations/xxxx_xx_xx_xxxxxx_create_viajes_table.php

    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained('rutas');
            $table->foreignId('empresa_id')->constrained('empresas_de_transporte');
            
            $table->date('fecha_salida');
            $table->time('hora_salida');
            $table->decimal('precio', 10, 2);
            
            $table->integer('asientos_totales');
            $table->integer('asientos_disponibles'); // Se reduce con cada reserva
            
            $table->string('tipo_servicio'); // Ej: "Cama", "Semi-cama"
            $table->string('estado')->default('programado'); // Ej: "programado", "cancelado"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
