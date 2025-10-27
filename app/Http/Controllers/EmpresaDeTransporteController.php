<?php

// app/Http/Controllers/EmpresaDeTransporteController.php

namespace App\Http\Controllers;

use App\Models\EmpresaDeTransporte;
use Illuminate\Http\Request;

class EmpresaDeTransporteController extends Controller
{
    // Mostrar todas las empresas de transporte
    public function index()
    {
        $empresas = EmpresaDeTransporte::all();
        return response()->json($empresas, 200);
    }

    // Crear una nueva empresa de transporte
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'required|string|max:255',
        ]);

        $empresa = EmpresaDeTransporte::create($validated);
        return response()->json($empresa, 201);
    }

    // Mostrar una empresa de transporte específica
    public function show($id)
    {
        $empresa = EmpresaDeTransporte::find($id);
        if (!$empresa) {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        }
        return response()->json($empresa, 200);
    }
}
