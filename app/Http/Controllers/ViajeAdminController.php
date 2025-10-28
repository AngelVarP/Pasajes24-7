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

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'empresa_id' => 'required|exists:empresas_de_transporte,id',
            'fecha_salida' => 'required|date|after_or_equal:today',
            'hora_salida' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
            'asientos_totales' => 'required|integer|min:1',
            'tipo_servicio' => 'required|string|max:255',
        ]);

        // ***** VALIDACIÓN DE HORA PARA HOY (CORREGIDA) *****
        $fechaSalida = Carbon::parse($validatedData['fecha_salida']);
        $horaSalida = Carbon::parse($validatedData['hora_salida']); // Carbon parseará "HH:MM" correctamente

        // Comparamos fecha y hora combinadas con el momento actual
        if ($fechaSalida->isToday() && $horaSalida->isBefore(now())) {
             throw ValidationException::withMessages([
                'hora_salida' => 'La hora de salida para hoy no puede ser en el pasado.',
             ]);
        }
        // *******************************************

        $viaje = Viaje::create([
            'ruta_id' => $validatedData['ruta_id'],
            'empresa_id' => $validatedData['empresa_id'],
            'fecha_salida' => $validatedData['fecha_salida'],
            'hora_salida' => $validatedData['hora_salida'] . ':00',
            'precio' => $validatedData['precio'],
            'asientos_totales' => $validatedData['asientos_totales'],
            'asientos_disponibles' => $validatedData['asientos_totales'],
            'tipo_servicio' => $validatedData['tipo_servicio'],
            'estado' => 'programado',
        ]);

        for ($i = 1; $i <= $viaje->asientos_totales; $i++) {
            Asiento::create([
                'viaje_id' => $viaje->id,
                'numero_asiento' => (string)$i,
                'piso' => 1,
                'estado' => 'disponible',
                'precio_adicional' => 0.00
            ]);
        }

        return redirect()->route('admin.viajes.index')->with('success', 'Viaje creado exitosamente.');
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
        $validatedData = $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'empresa_id' => 'required|exists:empresas_de_transporte,id',
            'fecha_salida' => 'required|date|after_or_equal:today',
            'hora_salida' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
            'asientos_totales' => 'required|integer|min:1',
            'tipo_servicio' => 'required|string|max:255',
        ]);

        // ***** VALIDACIÓN DE HORA PARA HOY (CORREGIDA) *****
        $fechaSalida = Carbon::parse($validatedData['fecha_salida']);
        $horaSalida = Carbon::parse($validatedData['hora_salida']);

        // Comparamos fecha y hora combinadas con el momento actual
        // Solo validamos si la fecha ES hoy. Si es una fecha futura, la hora no importa.
        if ($fechaSalida->isToday() && $horaSalida->isBefore(now())) {
             throw ValidationException::withMessages([
                'hora_salida' => 'La hora de salida para hoy no puede ser en el pasado.',
             ]);
        }
        // *******************************************

        // --- Lógica de Asientos ---
        $asientosOcupados = $viaje->asientos()->whereIn('estado', ['ocupado', 'reservado'])->count();
        if ($validatedData['asientos_totales'] < $asientosOcupados) {
             return back()->withErrors(['asientos_totales' => 'No puedes reducir el total de asientos a menos de la cantidad ya ocupada ('.$asientosOcupados.').']);
        }

        // Actualizar el viaje
        $viaje->update([
            'ruta_id' => $validatedData['ruta_id'],
            'empresa_id' => $validatedData['empresa_id'],
            'fecha_salida' => $validatedData['fecha_salida'],
            'hora_salida' => $validatedData['hora_salida'] . ':00',
            'precio' => $validatedData['precio'],
            'asientos_totales' => $validatedData['asientos_totales'],
            'asientos_disponibles' => $validatedData['asientos_totales'] - $asientosOcupados,
            'tipo_servicio' => $validatedData['tipo_servicio'],
        ]);

        // (OPCIONAL) Lógica para crear/eliminar asientos si cambia capacidad...

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
        if ($viaje->estado === 'programado' || $viaje->estado === 'en_curso') {
            $viaje->update(['estado' => 'cancelado']);
            return redirect()->route('admin.viajes.index')->with('success', 'Viaje #'.$viaje->id.' ha sido cancelado.');
        }
        return redirect()->route('admin.viajes.index')->with('error', 'El viaje #'.$viaje->id.' no puede ser cancelado.');
    }

/**
     * Actualiza manualmente los estados de los viajes a 'en_curso' o 'completado'.
     * Ejecuta la misma lógica que el comando programado, pero bajo demanda.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizarEstadosManualmente()
    {
        $now = Carbon::now();
        $updatedEnCurso = 0;
        $updatedCompletado = 0;

        // 1. Marcar como "en_curso"
        $viajesParaEmpezar = Viaje::where('estado', 'programado')
                                    ->whereRaw("CONCAT(fecha_salida, ' ', hora_salida) <= ?", [$now->toDateTimeString()])
                                    ->get();

        foreach ($viajesParaEmpezar as $viaje) {
            $viaje->update(['estado' => 'en_curso']);
            $updatedEnCurso++;
        }

        // 2. Marcar como "completado"
        $viajesParaCompletar = Viaje::where('estado', 'en_curso')
                                      ->with('ruta') // Necesitamos la duración
                                      ->get()
                                      ->filter(function ($viaje) use ($now) {
                                          if (empty($viaje->ruta->duracion_estimada_minutos)) return false;

                                          // Calcula la hora de llegada estimada
                                          $horaSalida = Carbon::parse($viaje->fecha_salida . ' ' . $viaje->hora_salida);
                                          $horaLlegadaEstimada = $horaSalida->addMinutes($viaje->ruta->duracion_estimada_minutos);

                                          return $now->isAfter($horaLlegadaEstimada); 
                                      });

        foreach ($viajesParaCompletar as $viaje) {
            $viaje->update(['estado' => 'completado']);
            $updatedCompletado++;
        }

        // Construir mensaje de éxito
        $message = "Actualización de estados completada. ";
        if ($updatedEnCurso > 0) $message .= "$updatedEnCurso viajes marcados como 'en curso'. ";
        if ($updatedCompletado > 0) $message .= "$updatedCompletado viajes marcados como 'completado'. ";
        if ($updatedEnCurso == 0 && $updatedCompletado == 0) $message .= "No hubo cambios.";

        // Redirigir de vuelta a la lista de viajes con el mensaje
        return redirect()->route('admin.viajes.index')->with('success', $message);
    }


}