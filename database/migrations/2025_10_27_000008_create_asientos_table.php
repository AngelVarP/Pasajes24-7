<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    // database/migrations/xxxx_xx_xx_xxxxxx_create_asientos_table.php

    public function up(): void
    {
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viaje_id')->constrained('viajes')->onDelete('cascade'); // Si se borra el viaje, se borran los asientos
            
            $table->string('numero_asiento'); // Ej: "5" o "5A"
            $table->integer('piso')->default(1);
            $table->string('estado')->default('disponible'); // "disponible", "ocupado"
            $table->decimal('precio_adicional', 8, 2)->default(0); // Por si es un asiento VIP
            
            $table->timestamps();

            // Un asiento debe ser único por viaje (no puede haber dos asientos "5" en el mismo viaje)
            $table->unique(['viaje_id', 'numero_asiento', 'piso']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
