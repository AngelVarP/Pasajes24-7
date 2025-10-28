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

    // ... (después del método 'store') ...

    /**
     * Muestra el formulario pre-rellenado para editar un viaje específico.
     *
     * @param  \App\Models\Viaje  $viaje  (Laravel inyecta el modelo Viaje basado en el ID de la ruta)
     * @return \Illuminate\View\View
     */
    public function edit(Viaje $viaje)
    {
        // Necesitamos las rutas y empresas para los <select> del formulario, igual que en create()
        $rutas = Ruta::with(['origen', 'destino'])->get(); 
        $empresas = EmpresaDeTransporte::all(); 

        // Pasa el viaje a editar, y las listas de rutas/empresas, a la vista de edición.
        return view('admin.viajes.edit', compact('viaje', 'rutas', 'empresas'));
    }

    /**
     * Actualiza un viaje específico en la base de datos con los datos del formulario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Viaje  $viaje  (Laravel inyecta el modelo Viaje)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Viaje $viaje)
    {
        // 1. Validación de los datos del formulario
        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'empresa_id' => 'required|exists:empresas_de_transporte,id',
            'fecha_salida' => 'required|date|after_or_equal:today',
            'hora_salida' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
            // Ojo: cambiar asientos_totales es delicado si ya hay reservas.
            // Por ahora, lo permitimos, pero con una validación básica.
            'asientos_totales' => 'required|integer|min:1', 
            'tipo_servicio' => 'required|string|max:255',
        ]);
        
        // --- Lógica de Asientos (Gestión de capacidad al reducir) ---
        // Si el admin intenta reducir la capacidad total de asientos,
        // debemos asegurarnos de que no sea menor al número de asientos ya ocupados/reservados.
        $asientosOcupados = $viaje->asientos()->whereIn('estado', ['ocupado', 'reservado'])->count();

        if ($request->asientos_totales < $asientosOcupados) {
             return back()->withErrors(['asientos_totales' => 'No puedes reducir el total de asientos a menos de la cantidad ya ocupada ('.$asientosOcupados.').']);
        }

        // 2. Actualizar los atributos del viaje
        $viaje->update([
            'ruta_id' => $request->ruta_id,
            'empresa_id' => $request->empresa_id,
            'fecha_salida' => $request->fecha_salida,
            'hora_salida' => $request->hora_salida . ':00', // Asegura formato HH:MM:SS
            'precio' => $request->precio,
            'asientos_totales' => $request->asientos_totales,
            // Recalculamos asientos disponibles con el nuevo total
            'asientos_disponibles' => $request->asientos_totales - $asientosOcupados, 
            'tipo_servicio' => $request->tipo_servicio,
            // El estado normalmente no se cambia aquí, sino en la acción de "Cancelar".
        ]);

        // 3. Redirigir de vuelta a la lista con un mensaje de éxito
        return redirect()->route('admin.viajes.index')->with('success', 'Viaje actualizado exitosamente!');
    }



// ... (después del método 'update') ...

    /**
     * Cancela un viaje específico, cambiando su estado a 'cancelado'.
     *
     * @param  \App\Models\Viaje  $viaje
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelar(Viaje $viaje)
    {
        // Solo permitir cancelar si el viaje está 'programado' o 'en_curso'
        if ($viaje->estado === 'programado' || $viaje->estado === 'en_curso') {
            $viaje->update(['estado' => 'cancelado']);
            
            // Opcional: Liberar asientos reservados/ocupados o reembolsar.
            // Por ahora, solo cambiamos el estado del viaje.
            // Si hay asientos reservados, podrías marcarlos como 'cancelado' también.
            // $viaje->asientos()->where('estado', 'reservado')->update(['estado' => 'cancelado']);

            return redirect()->route('admin.viajes.index')->with('success', 'Viaje #'.$viaje->id.' ha sido cancelado exitosamente.');
        }

        return redirect()->route('admin.viajes.index')->with('error', 'El viaje #'.$viaje->id.' no puede ser cancelado en su estado actual ('.$viaje->estado.').');
    }



}