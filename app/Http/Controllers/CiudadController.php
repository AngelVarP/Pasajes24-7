<?php

namespace App\Http\Controllers;

use App\Models\Ruta; // Necesitas importar el modelo Ruta
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Necesario para consultas más directas si se requiere

class CiudadController extends Controller
{
    /**
     * Busca ciudades/terminales que coincidan con la consulta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar(Request $request)
    {
        $query = $request->input('q'); // Obtiene el parámetro de búsqueda 'q'

        if (!$query) {
            return response()->json([]); // Devuelve vacío si no hay consulta
        }

        // Buscar en la columna 'origen'
        $origenes = Ruta::select('origen as nombre')
                        ->where('origen', 'LIKE', $query . '%')
                        ->distinct()
                        ->limit(5) // Limita el número de resultados
                        ->pluck('nombre'); // Obtiene solo la columna 'nombre'

        // Buscar en la columna 'destino'
        $destinos = Ruta::select('destino as nombre')
                        ->where('destino', 'LIKE', $query . '%')
                        ->distinct()
                        ->limit(5) // Limita el número de resultados
                        ->pluck('nombre'); // Obtiene solo la columna 'nombre'

        // Combinar resultados y eliminar duplicados
        $ciudades = $origenes->merge($destinos)->unique()->values();

        // Podrías ordenar alfabéticamente si lo deseas
        // $ciudades = $ciudades->sort()->values();

        return response()->json($ciudades); // Devuelve el resultado como JSON
    }
}