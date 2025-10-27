<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudad; // Asegúrate de importar el modelo Ciudad

class CiudadController extends Controller
{
    /**
     * Busca ciudades por nombre para autocompletado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar(Request $request)
    {
        \Log::info('Búsqueda de ciudad iniciada', ['query' => $request->input('q')]);
        $query = $request->input('q');

        if (empty($query)) {
            return response()->json([]);
        }

        if (empty($query)) {
            return response()->json([]); // Si la consulta está vacía, devuelve un array vacío
        }

        $ciudades = Ciudad::where('nombre', 'like', '%' . $query . '%')
                          ->orderBy('nombre')
                          ->get(); // Obtener el objeto completo para poder formatear

        // Mapear los resultados para devolver un formato específico: "Nombre (Departamento)"
        $formattedCiudades = $ciudades->map(function ($ciudad) {
            return $ciudad->nombre . ($ciudad->departamento ? ' (' . $ciudad->departamento . ')' : '');
        });

        // Para depuración:
        // return response()->json(['query' => $query, 'raw_ciudades' => $ciudades, 'formatted_ciudades' => $formattedCiudades]);

        return response()->json($formattedCiudades); // Devolver un array de strings formateados
    }
}