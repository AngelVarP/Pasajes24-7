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
        Schema::table('viajes', function (Blueprint $table) {
            $table->timestamp('hora_llegada_real')->nullable()->after('hora_salida');
            $table->unsignedInteger('minutos_retraso')->nullable()->after('hora_llegada_real');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropColumn(['hora_llegada_real', 'minutos_retraso']);
        });
    }
};
