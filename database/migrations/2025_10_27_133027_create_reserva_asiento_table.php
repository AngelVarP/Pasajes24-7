<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    // database/migrations/xxxx_xx_xx_xxxxxx_create_reserva_asiento_table.php

    public function up(): void
    {
        Schema::create('reserva_asiento', function (Blueprint $table) {
            $table->foreignId('reserva_id')->constrained('reservas')->onDelete('cascade');
            $table->foreignId('asiento_id')->constrained('asientos')->onDelete('cascade');
            
            // Clave primaria compuesta
            $table->primary(['reserva_id', 'asiento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_asiento');
    }
};
