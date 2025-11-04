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
        Schema::create('viaje_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viaje_id')->constrained('viajes')->cascadeOnDelete();
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo')->nullable();
            $table->string('tipo_evento');
            $table->json('detalles')->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_tipo')->default('sistema');
            $table->timestamps();

            $table->index(['viaje_id', 'tipo_evento']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viaje_eventos');
    }
};
