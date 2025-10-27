<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudad; // ¡Importa el nuevo modelo Ciudad!

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
        $query = $request->input('q'); // 'q' es el parámetro de búsqueda, ej: ?q=lim

        if (empty($query)) {
            return response()->json([]); // Si no hay query, devuelve un array vacío
        }

        $ciudades = Ciudad::where('nombre', 'like', '%' . $query . '%')
                          ->orderBy('nombre') // Opcional: ordenar alfabéticamente
                          ->get(['nombre']); // Solo queremos el nombre de la ciudad

        // Si quieres incluir el departamento para mayor claridad, puedes hacer esto:
        // $ciudades = Ciudad::where('nombre', 'like', '%' . $query . '%')
        //                   ->orderBy('nombre')
        //                   ->get()
        //                   ->map(function ($ciudad) {
        //                       return $ciudad->nombre . ($ciudad->departamento ? ' (' . $ciudad->departamento . ')' : '');
        //                   });


        return response()->json($ciudades->pluck('nombre')); // Devuelve solo los nombres como un array plano
    }
}