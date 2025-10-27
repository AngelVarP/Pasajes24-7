<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    // database/migrations/2025_10_27_031344_crear_tabla_empresas_de_transporte.php

    public function up(): void
    {
        Schema::create('empresas_de_transporte', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ruc', 11)->unique()->nullable();
            $table->string('logo_url')->nullable(); // URL para el logo
            $table->string('email_contacto')->nullable();
            $table->string('telefono_contacto')->nullable();
            $table->timestamps();
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
