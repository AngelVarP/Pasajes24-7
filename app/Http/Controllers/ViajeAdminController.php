<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruta; 
use App\Models\EmpresaDeTransporte; 
use App\Models\Viaje; // <--- ¡ASEGÚRATE DE QUE ESTA LÍNEA ESTÉ PRESENTE!
use App\Models\Asiento; 
use Illuminate\Validation\ValidationException; 
use Carbon\Carbon; // Para manejar fechas si es necesario en la vista o lógica

class ViajeAdminController extends Controller
{
    /**
     * Muestra el listado paginado de viajes para el administrador.
     * Corresponde a la sección "Gestionar Viajes".
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene todos los viajes, cargando las relaciones necesarias (Ruta, Origen, Destino, Empresa)
        // para poder mostrar sus nombres directamente en la tabla.
        // Los ordena por fecha y hora de salida de forma descendente y los pagina.
        $viajes = Viaje::with(['ruta.origen', 'ruta.destino', 'empresa'])
                        ->orderBy('fecha_salida', 'asc')
                        ->orderBy('hora_salida', 'asc')
                        ->paginate(15); // Muestra 15 viajes por página

        // Pasa la colección de viajes paginados a la vista 'admin.viajes.index'
        return view('admin.viajes.index', compact('viajes'));
    }

    /**
     * Muestra el formulario para crear un nuevo viaje.
     */
    public function create()
    {
        $rutas = Ruta::with(['origen', 'destino'])->get(); 
        $empresas = EmpresaDeTransporte::all(); 

        return view('admin.viajes.create', compact('rutas', 'empresas'));
    }

    /**
     * Guarda un nuevo viaje en la base de datos y genera sus asientos.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'empresa_id' => 'required|exists:empresas_de_transporte,id',
            'fecha_salida' => 'required|date|after_or_equal:today',
            'hora_salida' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
            'asientos_totales' => 'required|integer|min:1',
            'tipo_servicio' => 'required|string|max:255',
        ]);

        // Crear el nuevo viaje
        $viaje = Viaje::create([
            'ruta_id' => $request->ruta_id,
            'empresa_id' => $request->empresa_id,
            'fecha_salida' => $request->fecha_salida,
            'hora_salida' => $request->hora_salida . ':00', // Asegura formato HH:MM:SS
            'precio' => $request->precio,
            'asientos_totales' => $request->asientos_totales,
            'asientos_disponibles' => $request->asientos_totales, 
            'tipo_servicio' => $request->tipo_servicio,
            'estado' => 'programado', 
        ]);

        // Generar los asientos individuales para el viaje
        for ($i = 1; $i <= $viaje->asientos_totales; $i++) {
            Asiento::create([
                'viaje_id' => $viaje->id,
                'numero_asiento' => (string)$i, // Convertir a string para consistencia con DB
                'piso' => 1, 
                'estado' => 'disponible',
                'precio_adicional' => 0, 
            ]);
        }

        return redirect()->route('admin.viajes.index')->with('success', 'Viaje creado exitosamente y asientos generados.');
    }
}