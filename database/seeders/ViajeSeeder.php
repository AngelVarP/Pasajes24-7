<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Viaje;
use App\Models\Ruta;
use App\Models\EmpresaDeTransporte;
use App\Models\Asiento; // Necesario para crear asientos por viaje
use Carbon\Carbon; // Para manejar fechas y horas

class ViajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cruzDelSur = EmpresaDeTransporte::where('nombre', 'Cruz del Sur')->firstOrFail();
        $oltursa = EmpresaDeTransporte::where('nombre', 'Oltursa')->firstOrFail();
        $civa = EmpresaDeTransporte::where('nombre', 'Civa')->firstOrFail();

        $rutaLimaArequipa = Ruta::whereHas('origen', fn($q) => $q->where('nombre', 'Lima'))
                                ->whereHas('destino', fn($q) => $q->where('nombre', 'Arequipa'))
                                ->firstOrFail();

        $rutaLimaCusco = Ruta::whereHas('origen', fn($q) => $q->where('nombre', 'Lima'))
                             ->whereHas('destino', fn($q) => $q->where('nombre', 'Cusco'))
                             ->firstOrFail();

        $rutaArequipaLima = Ruta::whereHas('origen', fn($q) => $q->where('nombre', 'Arequipa'))
                                ->whereHas('destino', fn($q) => $q->where('nombre', 'Lima'))
                                ->firstOrFail();

        $rutaLimaTrujillo = Ruta::whereHas('origen', fn($q) => $q->where('nombre', 'Lima'))
                                ->whereHas('destino', fn($q) => $q->where('nombre', 'Trujillo'))
                                ->firstOrFail();

        // Fechas para los viajes (hoy, mañana, pasado mañana)
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $dayAfterTomorrow = Carbon::tomorrow()->addDay();

        $viajes = [
            // Lima -> Arequipa
            [
                'ruta_id' => $rutaLimaArequipa->id,
                'empresa_id' => $cruzDelSur->id,
                'fecha_salida' => $today->format('Y-m-d'),
                'hora_salida' => '20:00:00',
                'precio' => 85.00,
                'asientos_totales' => 40,
                'asientos_disponibles' => 40,
                'tipo_servicio' => 'Cama',
            ],
            [
                'ruta_id' => $rutaLimaArequipa->id,
                'empresa_id' => $oltursa->id,
                'fecha_salida' => $tomorrow->format('Y-m-d'),
                'hora_salida' => '21:30:00',
                'precio' => 95.00,
                'asientos_totales' => 35,
                'asientos_disponibles' => 35,
                'tipo_servicio' => 'Semi-cama',
            ],
            [
                'ruta_id' => $rutaLimaArequipa->id,
                'empresa_id' => $civa->id,
                'fecha_salida' => $dayAfterTomorrow->format('Y-m-d'),
                'hora_salida' => '19:00:00',
                'precio' => 70.00,
                'asientos_totales' => 45,
                'asientos_disponibles' => 45,
                'tipo_servicio' => 'Estándar',
            ],

            // Lima -> Cusco
            [
                'ruta_id' => $rutaLimaCusco->id,
                'empresa_id' => $cruzDelSur->id,
                'fecha_salida' => $today->format('Y-m-d'),
                'hora_salida' => '15:00:00',
                'precio' => 120.00,
                'asientos_totales' => 40,
                'asientos_disponibles' => 40,
                'tipo_servicio' => 'Cama VIP',
            ],
            [
                'ruta_id' => $rutaLimaCusco->id,
                'empresa_id' => $oltursa->id,
                'fecha_salida' => $tomorrow->format('Y-m-d'),
                'hora_salida' => '16:00:00',
                'precio' => 110.00,
                'asientos_totales' => 30,
                'asientos_disponibles' => 30,
                'tipo_servicio' => 'Semi-cama',
            ],

            // Arequipa -> Lima
            [
                'ruta_id' => $rutaArequipaLima->id,
                'empresa_id' => $civa->id,
                'fecha_salida' => $today->format('Y-m-d'),
                'hora_salida' => '10:00:00',
                'precio' => 80.00,
                'asientos_totales' => 40,
                'asientos_disponibles' => 40,
                'tipo_servicio' => 'Estándar',
            ],
            [
                'ruta_id' => $rutaArequipaLima->id,
                'empresa_id' => $cruzDelSur->id,
                'fecha_salida' => $tomorrow->format('Y-m-d'),
                'hora_salida' => '14:00:00',
                'precio' => 90.00,
                'asientos_totales' => 38,
                'asientos_disponibles' => 38,
                'tipo_servicio' => 'Cama',
            ],

             // Lima -> Trujillo
             [
                'ruta_id' => $rutaLimaTrujillo->id,
                'empresa_id' => $oltursa->id,
                'fecha_salida' => $today->format('Y-m-d'),
                'hora_salida' => '22:00:00',
                'precio' => 60.00,
                'asientos_totales' => 40,
                'asientos_disponibles' => 40,
                'tipo_servicio' => 'Semi-cama',
            ],
        ];

        foreach ($viajes as $viajeData) {
            $viaje = Viaje::firstOrCreate([
                'ruta_id' => $viajeData['ruta_id'],
                'empresa_id' => $viajeData['empresa_id'],
                'fecha_salida' => $viajeData['fecha_salida'],
                'hora_salida' => $viajeData['hora_salida'],
            ], $viajeData);

            // Crear asientos para este viaje recién creado
            if ($viaje->wasRecentlyCreated || $viaje->asientos->isEmpty()) { // Solo si es nuevo o no tiene asientos
                for ($i = 1; $i <= $viaje->asientos_totales; $i++) {
                    Asiento::firstOrCreate([
                        'viaje_id' => $viaje->id,
                        'numero_asiento' => (string)$i, // Asegurarse de que sea string
                        'piso' => 1, // Por defecto, todos en el piso 1
                    ], [
                        'estado' => 'disponible',
                        'precio_adicional' => ($i <= 4 || $i > ($viaje->asientos_totales - 4)) ? 5.00 : 0, // Ej. asientos de adelante/atrás más caros
                    ]);
                }
            }
        }
    }
}