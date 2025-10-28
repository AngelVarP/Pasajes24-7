<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViajeAdminController extends Controller
{
    public function create()
    {
        return view('admin.viajes.create');
    }

    public function store(Request $request)
    {
        // Validación y guardado temporal
        $request->validate([
            'origen' => 'required|string',
            'destino' => 'required|string',
        ]);

        // Aquí guardarías el viaje

        return redirect()->route('admin.viajes.create')->with('success', 'Viaje creado (stub).');
    }
}
