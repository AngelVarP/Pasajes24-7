<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruta; // <-- Necesario para el create()
use App\Models\EmpresaDeTransporte; // <-- Necesario para el create()
use App\Models\Viaje; // <-- Necesario para el store()
use App\Models\Asiento; // <-- Necesario para el store()

class ViajeAdminController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo viaje, pasando las rutas y empresas.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Esta lógica es necesaria para llenar los <select> del formulario
        $rutas = Ruta::with(['origen', 'destino'])->get(); 
        $empresas = EmpresaDeTransporte::all(); 

        return view('admin.viajes.create', compact('rutas', 'empresas'));
    }

    /**
     * Guarda un nuevo viaje en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario (CORREGIDA)
        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'empresa_id' => 'required|exists:empresas_de_transporte,id',
            'fecha_salida' => 'required|date|after_or_equal:today',
            'hora_salida' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
            'asientos_totales' => 'required|integer|min:1',
            'tipo_servicio' => 'required|string|max:255',
        ]);

        // 2. Crear el nuevo viaje en la tabla 'viajes'
        $viaje = Viaje::create([
            'ruta_id' => $request->ruta_id,
            'empresa_id' => $request->empresa_id,
            'fecha_salida' => $request->fecha_salida,
            'hora_salida' => $request->hora_salida . ':00', // Añadir segundos
            'precio' => $request->precio,
            'asientos_totales' => $request->asientos_totales,
            'asientos_disponibles' => $request->asientos_totales, // Al inicio, todos disponibles
            'tipo_servicio' => $request->tipo_servicio,
            'estado' => 'programado', // Por defecto
        ]);

        // 3. Crear los asientos individuales para el viaje (en la tabla 'asientos')
        for ($i = 1; $i <= $viaje->asientos_totales; $i++) {
            Asiento::create([
                'viaje_id' => $viaje->id,
                'numero_asiento' => (string)$i,
                'piso' => 1, // Por defecto piso 1
                'estado' => 'disponible',
                'precio_adicional' => 0.00
            ]);
        }

        // 4. Redirigir con un mensaje de éxito
        return redirect()->route('admin.viajes.create')->with('success', 'Viaje creado exitosamente y asientos generados.');
    }
}