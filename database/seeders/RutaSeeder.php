<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta; // Importar el modelo Ruta
use App\Models\Ciudad; // Importar el modelo Ciudad

class RutaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que las ciudades existan antes de correr este seeder
        $lima = Ciudad::where('nombre', 'Lima')->firstOrFail();
        $arequipa = Ciudad::where('nombre', 'Arequipa')->firstOrFail();
        $cusco = Ciudad::where('nombre', 'Cusco')->firstOrFail();
        $trujillo = Ciudad::where('nombre', 'Trujillo')->firstOrFail();
        $piura = Ciudad::where('nombre', 'Piura')->firstOrFail();

        $rutas = [
            ['origen_id' => $lima->id, 'destino_id' => $arequipa->id, 'duracion_estimada_minutos' => 1020], // 17h
            ['origen_id' => $arequipa->id, 'destino_id' => $lima->id, 'duracion_estimada_minutos' => 1020],
            ['origen_id' => $lima->id, 'destino_id' => $cusco->id, 'duracion_estimada_minutos' => 1140], // 19h
            ['origen_id' => $cusco->id, 'destino_id' => $lima->id, 'duracion_estimada_minutos' => 1140],
            ['origen_id' => $lima->id, 'destino_id' => $trujillo->id, 'duracion_estimada_minutos' => 540], // 9h
            ['origen_id' => $trujillo->id, 'destino_id' => $lima->id, 'duracion_estimada_minutos' => 540],
            ['origen_id' => $lima->id, 'destino_id' => $piura->id, 'duracion_estimada_minutos' => 1440], // 24h
            ['origen_id' => $piura->id, 'destino_id' => $lima->id, 'duracion_estimada_minutos' => 1440],
            ['origen_id' => $arequipa->id, 'destino_id' => $cusco->id, 'duracion_estimada_minutos' => 360], // 6h
            ['origen_id' => $cusco->id, 'destino_id' => $arequipa->id, 'duracion_estimada_minutos' => 360],
        ];

        foreach ($rutas as $ruta) {
            Ruta::firstOrCreate(['origen_id' => $ruta['origen_id'], 'destino_id' => $ruta['destino_id']], $ruta);
        }
    }
}