<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Viaje; 
use App\Models\Asiento; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ReservaController extends Controller
{
    // Tu método index (no se toca)
    public function index()
    {
        $reservas = Reserva::all();
        return response()->json($reservas, 200);
    }

    /**
     * Paso 1: Recibe los asientos, bloquea temporalmente y muestra página de Datos de Pasajeros.
     */
    public function store(Request $request)
    {
        // 1. Validar la entrada (espera viaje_id y un array de asientos[])
        $request->validate([
            'viaje_id' => 'required|exists:viajes,id',
            'asientos' => 'required|array|min:1',
            'asientos.*' => 'required|exists:asientos,id', 
        ]);

        $viajeId = $request->viaje_id;
        $asientoIds = $request->asientos;

        try {
            DB::beginTransaction(); // <-- Inicia Transacción para bloquear

            // 2. Obtener el viaje y los asientos (con bloqueo pesimista)
            $viaje = Viaje::findOrFail($viajeId);
            $asientos = Asiento::whereIn('id', $asientoIds)
                                ->where('viaje_id', $viajeId)
                                ->lockForUpdate() // <--- Bloquea estos registros en la BD
                                ->get();

            // 3. Verificar disponibilidad y validez
            if (count($asientos) != count($asientoIds)) {
                DB::rollBack();
                return redirect()->back()->withErrors(['general' => 'Algunos asientos no son válidos para este viaje.']);
            }

            foreach ($asientos as $asiento) {
                if ($asiento->estado !== 'disponible') {
                    DB::rollBack();
                    return redirect()->back()->withErrors(['general' => "El asiento {$asiento->numero_asiento} acaba de ser ocupado. Por favor, intente de nuevo."]); 
                }
            }
            
            // 4. Calcular el total y guardar la información de bloqueo en la sesión
            $total = $viaje->precio * count($asientos);

            session(['reserva_temporal' => [
                'viaje_id' => $viaje->id,
                'asiento_ids' => $asientos->pluck('id')->toArray(),
                'total' => $total,
            ]]);
            
            // 5. Cargar datos para la vista de pasajeros (Paso 2)
            $viaje->load(['ruta.origen', 'ruta.destino', 'empresa']);
            $asientosSeleccionados = $asientos->pluck('numero_asiento'); // Números para el título

            // 6. Retorna la nueva vista (el formulario de datos)
            return view('reserva_pasajeros', compact('viaje', 'asientosSeleccionados', 'total'));


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en reserva temporal (store): " . $e->getMessage());
            return redirect()->route('home')->withErrors(['general' => 'Ocurrió un error inesperado al procesar tu selección.']);
        }
    }
    
    // Tu método show (no se toca)
    public function show($id)
    {
        $reserva = Reserva::find($id);
        if (!$reserva) {
            return response()->json(['error' => 'Reserva no encontrada'], 404);
        }
        return response()->json($reserva, 200);
    }
    
    /**
     * Paso 2: Recibe y valida los datos de pasajeros/comprador y procede al pago.
     */
    public function procesarPasajeros(Request $request)
    {
        // Lógica de validación y pago futura
        
        // Aquí debes validar toda la información: 
        // 1. Que haya N pasajeros donde N es la cantidad de asiento_ids en sesión.
        // 2. Que todos los campos de pasajero (nombre, dni, edad) y comprador (email, nombre, dni) sean válidos.
        
        // dd() para debug: Muestra los datos de la sesión y el formulario
        dd('Lógica de validación de pasajeros y Redirección a Pago/Confirmación Final Pendiente', $request->all(), session('reserva_temporal'));
    }
}