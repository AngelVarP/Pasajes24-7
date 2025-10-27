<?php

// app/Http/Controllers/RutaController.php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    // Mostrar todas las rutas
    public function index()
    {
        $rutas = Ruta::all();
        return response()->json($rutas, 200);
    }

    // Crear una nueva ruta
    public function store(Request $request)
    {
        $validated = $request->validate([
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora_salida' => 'required|date_format:H:i',
            'hora_llegada' => 'required|date_format:H:i',
        ]);

        $ruta = Ruta::create($validated);
        return response()->json($ruta, 201);
    }

    // Mostrar una ruta específica
    public function show($id)
    {
        $ruta = Ruta::find($id);
        if (!$ruta) {
            return response()->json(['error' => 'Ruta no encontrada'], 404);
        }
        return response()->json($ruta, 200);
    }
}
