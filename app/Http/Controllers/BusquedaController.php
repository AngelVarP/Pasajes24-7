<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viaje; // Importamos el modelo Viaje
use App\Models\Ciudad; // Importamos el modelo Ciudad
use Carbon\Carbon;

class BusquedaController extends Controller
{
    /**
     * Busca viajes disponibles según el origen, destino y fecha.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function buscar(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'origen' => 'required|string',
            'destino' => 'required|string',
            'fecha' => 'required|date',
        ]);

        // 2. Obtener los IDs de las ciudades (manejando el formato "Nombre (Departamento)")
        $origenNombre = explode(' (', $request->origen)[0];
        $destinoNombre = explode(' (', $request->destino)[0];

        $origen = Ciudad::where('nombre', $origenNombre)->first();
        $destino = Ciudad::where('nombre', $destinoNombre)->first();

        // Manejar si las ciudades no se encuentran
        if (!$origen || !$destino) {
            return back()->withErrors(['error' => 'La ciudad de origen o destino no es válida. Por favor, usa las sugerencias.']);
        }

        $fechaBuscada = Carbon::parse($request->fecha)->startOfDay();

        // 3. Realizar la consulta
        $viajes = Viaje::whereHas('ruta', function ($query) use ($origen, $destino) {
            // Buscar en la tabla 'rutas' usando los IDs de ciudad
            $query->where('origen_id', $origen->id)
                  ->where('destino_id', $destino->id);
        })
        
        ->whereDate('fecha_salida', $fechaBuscada)
        // ***** MODIFICACIÓN AQUÍ *****
        ->whereNotIn('estado', ['cancelado', 'en_curso', 'completado']) // Solo mostrar 'programado'
        // *******************************
        ->when($fechaBuscada->isToday(), function ($query) {
             $query->whereTime('hora_salida', '>', now()->format('H:i:s'));
        })
        ->with(['empresa', 'ruta.origen', 'ruta.destino'])
        ->orderBy('hora_salida')
        ->get();



        // 4. Devolver la vista con los resultados
        return view('resultados', [
            'viajes' => $viajes,
            'origen' => $origen,
            'destino' => $destino,
            'fecha' => $request->fecha,
        ]);
    }

        /**
     * Muestra la página de selección de asientos para un viaje específico.
     *
     * @param  \App\Models\Viaje  $viaje  Laravel inyecta el Viaje basado en el {viaje} de la URL
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    /**
     * Muestra la página de selección de asientos para un viaje específico.
     *
     * @param  \App\Models\Viaje  $viaje
     * @return \Illuminate\View\View
     */
    public function mostrarAsientos(Viaje $viaje)
    {
        // Cargar las relaciones necesarias para mostrar los detalles del viaje
        $viaje->load(['ruta.origen', 'ruta.destino', 'empresa']);

        // Cargar los asientos de este viaje.
        // La vista 'asientos.blade.php' se encargará de la posición
        // (Esto asume que ya ejecutaste la migración y el seeder de la Alternativa 2)
        $asientos = $viaje->asientos; 

        // Devolvemos la vista 'asientos' con toda la información
        return view('asientos', compact('viaje', 'asientos'));
    }
    // --------------------------------------------------












}