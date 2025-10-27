<?php

// app/Http/Controllers/ReservaController.php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Ruta;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Mostrar todas las reservas
    public function index()
    {
        $reservas = Reserva::all();
        return response()->json($reservas, 200);
    }

    // Crear una nueva reserva
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'ruta_id' => 'required|exists:rutas,id',
            'asiento' => 'required|string|max:255',
        ]);

        $reserva = Reserva::create($validated);
        return response()->json($reserva, 201);
    }

    // Mostrar una reserva específica
    public function show($id)
    {
        $reserva = Reserva::find($id);
        if (!$reserva) {
            return response()->json(['error' => 'Reserva no encontrada'], 404);
        }
        return response()->json($reserva, 200);
    }
}
