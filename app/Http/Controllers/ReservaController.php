<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Viaje; 
use App\Models\Asiento; 
use App\Models\ReservaAsiento; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str; 
// Asegúrate de que todos los 'use' necesarios estén aquí

class ReservaController extends Controller
{
    // Tu método index (no se toca)
    public function index()
    {
        $reservas = Reserva::all();
        return response()->json($reservas, 200);
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
     * Paso 1: Recibe los asientos (POST), guarda en sesión y REDIRIGE.
     */
    public function store(Request $request)
    {
        // 1. Validar la entrada
        $request->validate([
            'viaje_id' => 'required|exists:viajes,id',
            'asientos' => 'required|array|min:1',
            'asientos.*' => 'required|exists:asientos,id', 
        ]);

        $viajeId = $request->viaje_id;
        $asientoIds = $request->asientos;

        try {
            DB::beginTransaction(); 

            // 2. Obtener el viaje y los asientos (con bloqueo pesimista)
            $viaje = Viaje::findOrFail($viajeId);
            $asientos = Asiento::whereIn('id', $asientoIds)
                                ->where('viaje_id', $viajeId)
                                ->lockForUpdate() 
                                ->get();

            // 3. Verificar disponibilidad y validez
            if (count($asientos) != count($asientoIds)) {
                DB::rollBack();
                return redirect()->back()->withErrors(['general' => 'Algunos asientos no son válidos para este viaje.']);
            }

            // 4. Calcular el total y crear el desglose por asiento
            $total = 0;
            $asientosDetallados = [];
            
            foreach ($asientos as $asiento) {
                if ($asiento->estado !== 'disponible') {
                    DB::rollBack();
                    return redirect()->back()->withErrors(['general' => "El asiento {$asiento->numero_asiento} acaba de ser ocupado. Por favor, intente de nuevo."]); 
                }

                $precioBase = $viaje->precio;
                $precioAdicional = $asiento->precio_adicional ?? 0; 
                $precioUnitario = $precioBase + $precioAdicional;

                $total += $precioUnitario;

                $asientosDetallados[] = [
                    'id' => $asiento->id,
                    'numero_asiento' => $asiento->numero_asiento,
                    'piso' => $asiento->piso,
                    'precio_unitario' => $precioUnitario,
                    'precio_adicional' => $precioAdicional,
                ];
            }
            
            // 5. Guardar la información detallada en la sesión
            session(['reserva_temporal' => [
                'viaje_id' => $viaje->id,
                'asiento_ids' => $asientos->pluck('id')->toArray(),
                'asientos_detalles' => $asientosDetallados,
                'total' => $total,
            ]]);
            
            // 6. Confirmar transacción
            DB::commit();

            // 7. ¡CAMBIO CLAVE! Redirigir a la nueva ruta GET
            return redirect()->route('reservas.mostrar_pasajeros');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en reserva temporal (store): " . $e->getMessage());
            return redirect()->route('home')->withErrors(['general' => 'Ocurrió un error inesperado al procesar tu selección.']);
        }
    }
    

    /**
     * ¡NUEVO MÉTODO!
     * Paso 2: Muestra el formulario de datos de pasajeros (GET).
     */
    public function mostrarPasajeros()
    {
        // 1. Recuperar la información de la sesión
        $reservaTemporal = session('reserva_temporal');

        // 2. Verificar si la sesión existe
        if (!$reservaTemporal) {
            return redirect()->route('home')->withErrors(['general' => 'Tu sesión ha expirado. Por favor, inicia la búsqueda de nuevo.']);
        }

        // 3. Cargar los datos necesarios para la vista
        $viaje = Viaje::with(['ruta.origen', 'ruta.destino', 'empresa'])
                      ->findOrFail($reservaTemporal['viaje_id']);
        
        $asientosDetallados = $reservaTemporal['asientos_detalles'];
        $asientosSeleccionados = collect($asientosDetallados)->pluck('numero_asiento');
        $total = $reservaTemporal['total'];
        $asientosResumen = $asientosDetallados;

        // 4. Retornar la vista
        return view('reserva_pasajeros', compact('viaje', 'asientosSeleccionados', 'total', 'asientosResumen'));
    }



    /**
     * Paso 3: Recibe y valida los datos de pasajeros/comprador y crea la reserva final.
     * (Este método vuelve a tener la validación de Laravel, que ahora sí funcionará)
     */
    public function procesarPasajeros(Request $request)
    {
        // ¡¡IMPORTANTE!! ASEGÚRATE DE BORRAR EL dd(...) QUE PUSIMOS ANTES

        // 1. Recuperar la información de la sesión
        $reservaTemporal = session('reserva_temporal');

        // Verificar si la sesión expiró o no existe
        if (!$reservaTemporal) {
            return redirect()->route('home')->withErrors(['general' => 'Tu sesión ha expirado. Por favor, inicia la búsqueda de nuevo.']);
        }

        // 2. Definir reglas de validación
        $rules = [
            'comprador' => 'required|array',
            'comprador.nombre' => 'required|string|max:255',
            'comprador.dni' => 'required|string|max:11', // DNI o RUC
            'comprador.email' => 'required|email|max:255',
            'comprador.telefono' => 'nullable|string|max:15', 
            
            'pasajeros' => 'required|array',
            'pasajeros.*.nombre' => 'required|string|max:100',
            'pasajeros.*.apellido' => 'required|string|max:100',
            'pasajeros.*.dni' => 'required|string|digits:8',
            'pasajeros.*.edad' => 'required|integer|min:0|max:120',
        ];

        // 3. Validar los datos del formulario
        $validatedData = $request->validate($rules);

        // 4. Verificación de integridad (¡MEJORADA!)
        // Comparamos los pasajeros enviados con los asientos guardados en la sesión.
        // Usamos 'asientos_detalles' porque es la fuente de los formularios.
        if (count($validatedData['pasajeros']) !== count($reservaTemporal['asientos_detalles'])) {
             Log::warning('Discrepancia en conteo de pasajeros/asientos', [
                'pasajeros_enviados' => count($validatedData['pasajeros']),
                'asientos_en_sesion' => count($reservaTemporal['asientos_detalles'])
             ]);
             return redirect()->back()->withInput()->withErrors(['general' => 'El número de pasajeros no coincide con los asientos seleccionados.']);
        }

        // 5. Iniciar la transacción final
        try {
            DB::beginTransaction();

            // 6. Crear la Reserva principal
            $reserva = Reserva::create([
                'viaje_id' => $reservaTemporal['viaje_id'],
                'user_id' => Auth::id(), 
                'codigo_reserva' => strtoupper(Str::random(8)), 
                'nombre_comprador' => $validatedData['comprador']['nombre'],
                'dni_comprador' => $validatedData['comprador']['dni'],
                'email_comprador' => $validatedData['comprador']['email'],
                'telefono_comprador' => $validatedData['comprador']['telefono'],
                'monto_total' => $reservaTemporal['total'],
                'estado' => 'confirmada', 
            ]);

            // 7. Crear los registros de ReservaAsiento
            foreach ($validatedData['pasajeros'] as $index => $pasajeroData) {
                // Obtenemos los detalles del asiento guardados en la sesión
                // (Ahora estamos seguros que $index coincide)
                $asientoDetalle = $reservaTemporal['asientos_detalles'][$index];

                ReservaAsiento::create([
                    'reserva_id' => $reserva->id,
                    'asiento_id' => $asientoDetalle['id'],
                    'viaje_id' => $reservaTemporal['viaje_id'],
                    'precio' => $asientoDetalle['precio_unitario'],
                    'pasajero_nombre' => $pasajeroData['nombre'] . ' ' . $pasajeroData['apellido'],
                    'pasajero_dni' => $pasajeroData['dni'],
                    'pasajero_edad' => $pasajeroData['edad'],
                ]);
            }

            // 8. Actualizar el estado de los Asientos a 'ocupado'
            Asiento::whereIn('id', $reservaTemporal['asiento_ids'])
                   ->update(['estado' => 'ocupado']);

            // 9. Confirmar transacción
            DB::commit();

            // 10. Limpiamos la sesión
            session()->forget('reserva_temporal');

            // 11. Redirigimos a la página de confirmación
            return redirect()->route('reserva.confirmacion', $reserva->id); 

        } catch (\Exception $e) {
            // 12. Si algo falla, revertimos todo
            DB::rollBack();
            Log::error("Error al procesar reserva final: " . $e->getMessage());
            // ¡AHORA ESTE REDIRECT() SÍ FUNCIONARÁ!
            return redirect()->back()->withInput()->withErrors(['general' => 'No se pudo completar la reserva. Error de base de datos.']);
        }
    }

    /**
     * Paso 4: Muestra la página de confirmación de la reserva.
     * (Este método queda exactamente igual)
     */
    public function confirmacion(Reserva $reserva)
    {
        $reserva->load([
            'viaje.ruta.origen', 
            'viaje.ruta.destino', 
            'viaje.empresa', 
            'reservaAsientos.asiento' 
        ]);

        return view('reserva_confirmacion', compact('reserva'));
    }
}