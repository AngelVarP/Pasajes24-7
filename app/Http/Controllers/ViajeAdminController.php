<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruta; 
use App\Models\EmpresaDeTransporte; 
use App\Models\Viaje; 
use App\Models\Asiento; 
use Illuminate\Validation\ValidationException; 
use Carbon\Carbon; 

class ViajeAdminController extends Controller
{
    /**
     * Muestra el listado paginado de viajes para el administrador con filtros.
     * Criterios: Ruta, Empresa, Fecha de Salida, Servicio, Estado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 1. Obtener datos auxiliares para los selectores de filtro
        $rutas = Ruta::with(['origen', 'destino'])->orderBy('origen_id')->get();
        $empresas = EmpresaDeTransporte::orderBy('nombre')->get();
        
        // Asumiendo los posibles valores de estado y servicio del modelo Viaje
        $estadosDisponibles = ['programado', 'en_curso', 'completado', 'cancelado'];
        $serviciosDisponibles = ['economico', 'ejecutivo', 'vip']; // Ajustar si tienes más tipos

        // 2. Iniciar la consulta base (con Eager Loading)
        $viajesQuery = Viaje::with(['ruta.origen', 'ruta.destino', 'empresa'])
                             ->orderBy('fecha_salida', 'asc')
                             ->orderBy('hora_salida', 'asc');

        // 3. Aplicar Filtros Dinámicos
        
        // Filtro por Ruta (ID)
        if ($request->filled('ruta_id')) {
            $viajesQuery->where('ruta_id', $request->input('ruta_id'));
        }
        
        // Filtro por Empresa
        if ($request->filled('empresa_id')) {
            $viajesQuery->where('empresa_id', $request->input('empresa_id'));
        }

        // Filtro por Fecha de Salida (Exacta)
        if ($request->filled('fecha_salida')) {
            $viajesQuery->whereDate('fecha_salida', $request->input('fecha_salida'));
        }
        
        // Filtro por Servicio
        if ($request->filled('tipo_servicio')) {
            $viajesQuery->where('tipo_servicio', $request->input('tipo_servicio'));
        }
        
        // Filtro por Estado
        if ($request->filled('estado')) {
            $viajesQuery->where('estado', $request->input('estado'));
        }

        // 4. Ejecutar la consulta y paginar, manteniendo los filtros en la URL
        $viajes = $viajesQuery->paginate(15)->withQueryString(); 

        // 5. Devolver la vista con los viajes y los datos auxiliares
        return view('admin.viajes.index', compact(
            'viajes', 
            'rutas', 
            'empresas',
            'estadosDisponibles',
            'serviciosDisponibles'
        ));
    }

    /**
     * Muestra el formulario para crear un nuevo viaje.
     */
    public function create()
    {
        $rutas = Ruta::with(['origen', 'destino'])->get(); 
        $empresas = EmpresaDeTransporte::all(); 
        
        // Asumiendo los posibles valores de servicio y estado para el formulario
        $estadosDisponibles = ['programado', 'en_curso', 'completado', 'cancelado'];
        $serviciosDisponibles = ['economico', 'ejecutivo', 'vip']; 

        return view('admin.viajes.create', compact('rutas', 'empresas', 'estadosDisponibles', 'serviciosDisponibles'));
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
        $horaSalida = Carbon::parse($validatedData['hora_salida']); 

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
    
    // ... (rest of the methods: edit, update, cancelar, actualizarEstadosManualmente)
    
     /**
     * Muestra el formulario pre-rellenado para editar un viaje específico.
     *
     * @param  \App\Models\Viaje  $viaje 
     * @return \Illuminate\View\View
     */
    public function edit(Viaje $viaje)
    {
        $rutas = Ruta::with(['origen', 'destino'])->get(); 
        $empresas = EmpresaDeTransporte::all(); 
        $estadosDisponibles = ['programado', 'en_curso', 'completado', 'cancelado'];
        $serviciosDisponibles = ['economico', 'ejecutivo', 'vip']; 

        return view('admin.viajes.edit', compact('viaje', 'rutas', 'empresas', 'estadosDisponibles', 'serviciosDisponibles'));
    }

    // ... (update, cancelar, actualizarEstadosManualmente methods go here, unchanged from your original input)

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
