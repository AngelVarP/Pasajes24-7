<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // database/migrations/2025_10_27_031026_crear_tabla_rutas.php

    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            // Claves foráneas a la tabla 'ciudades'
            $table->foreignId('origen_id')->constrained('ciudades');
            $table->foreignId('destino_id')->constrained('ciudades');
            $table->integer('duracion_estimada_minutos')->nullable();
            $table->timestamps();

            // Asegurarse que no haya rutas duplicadas (ej. Lima -> Cusco)
            $table->unique(['origen_id', 'destino_id']);
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
