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
        Schema::create('empresas_de_transporte', function (Blueprint $table) {
            $table->id(); // ID de la empresa
            $table->string('nombre'); // Nombre de la empresa
            $table->string('contacto'); // Información de contacto (teléfono, email)
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas_de_transporte');
    }
};
