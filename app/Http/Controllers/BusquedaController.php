<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viaje; // Importamos el modelo Viaje
use App\Models\Ciudad; // Importamos el modelo Ciudad

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

        // 3. Realizar la consulta
        $viajes = Viaje::whereHas('ruta', function ($query) use ($origen, $destino) {
            // Buscar en la tabla 'rutas' usando los IDs de ciudad
            $query->where('origen_id', $origen->id)
                  ->where('destino_id', $destino->id);
        })
        ->whereDate('fecha_salida', $request->fecha) // Filtrar por la fecha exacta
        ->with(['empresa', 'ruta.origen', 'ruta.destino']) // Cargar relaciones para mostrarlas en la vista
        ->orderBy('hora_salida') // Ordenar por hora
        ->get();

        // 4. Devolver la vista con los resultados
        return view('resultados', [
            'viajes' => $viajes,
            'origen' => $origen,
            'destino' => $destino,
            'fecha' => $request->fecha,
        ]);
    }
}