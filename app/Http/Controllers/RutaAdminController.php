<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Necesario para validación única compuesta

class RutaAdminController extends Controller
{
    /**
     * Muestra la lista de rutas.
     */
    public function index()
    {
        // Cargamos las rutas CON sus relaciones de origen y destino
        $rutas = Ruta::with(['origen', 'destino'])->orderBy('origen_id')->paginate(15);
        // Usamos la vista correcta
        return view('admin.rutas.index', compact('rutas'));
    }

    /**
     * Muestra el formulario para crear una nueva ruta.
     */
    public function create()
    {
        $ciudades = Ciudad::orderBy('nombre')->get(); // Para los dropdowns
        // Usamos la vista correcta
        return view('admin.rutas.create', compact('ciudades'));
    }

    /**
     * Guarda una nueva ruta en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'origen_id' => [
                'required',
                'exists:ciudades,id',
                'different:destino_id',
                 Rule::unique('rutas')->where(function ($query) use ($request) {
                    return $query->where('destino_id', $request->destino_id);
                }),
            ],
            'destino_id' => 'required|exists:ciudades,id',
            'duracion_estimada_minutos' => 'required|integer|min:1',
        ],[
            'origen_id.required' => 'Debes seleccionar una ciudad de origen.',
            'origen_id.exists' => 'La ciudad de origen seleccionada no es válida.',
            'origen_id.different' => 'El origen y el destino no pueden ser la misma ciudad.',
            'origen_id.unique' => 'Ya existe una ruta con este origen y destino.',
            'destino_id.required' => 'Debes seleccionar una ciudad de destino.',
            'destino_id.exists' => 'La ciudad de destino seleccionada no es válida.',
            'duracion_estimada_minutos.required' => 'Debes ingresar la duración estimada.',
            'duracion_estimada_minutos.integer' => 'La duración debe ser un número entero.',
            'duracion_estimada_minutos.min' => 'La duración debe ser de al menos 1 minuto.',
        ]);

        Ruta::create($request->all());

        return redirect()->route('admin.rutas.index')
                         ->with('success', 'Ruta creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una ruta existente.
     */
    public function edit(Ruta $ruta) // Usamos Route Model Binding
    {
        $ciudades = Ciudad::orderBy('nombre')->get();
        // Usamos la vista correcta
        return view('admin.rutas.edit', compact('ruta', 'ciudades'));
    }

    /**
     * Actualiza una ruta existente en la base de datos.
     */
    public function update(Request $request, Ruta $ruta)
    {
         $request->validate([
            'origen_id' => [
                'required',
                'exists:ciudades,id',
                'different:destino_id',
                 Rule::unique('rutas')->where(function ($query) use ($request) {
                    return $query->where('destino_id', $request->destino_id);
                })->ignore($ruta->id),
            ],
            'destino_id' => 'required|exists:ciudades,id',
            'duracion_estimada_minutos' => 'required|integer|min:1',
        ],[
            'origen_id.required' => 'Debes seleccionar una ciudad de origen.',
            'origen_id.exists' => 'La ciudad de origen seleccionada no es válida.',
            'origen_id.different' => 'El origen y el destino no pueden ser la misma ciudad.',
            'origen_id.unique' => 'Ya existe otra ruta con este origen y destino.',
            'destino_id.required' => 'Debes seleccionar una ciudad de destino.',
            'destino_id.exists' => 'La ciudad de destino seleccionada no es válida.',
            'duracion_estimada_minutos.required' => 'Debes ingresar la duración estimada.',
            'duracion_estimada_minutos.integer' => 'La duración debe ser un número entero.',
            'duracion_estimada_minutos.min' => 'La duración debe ser de al menos 1 minuto.',
        ]);

        $ruta->update($request->all());

        return redirect()->route('admin.rutas.index')
                         ->with('success', 'Ruta actualizada exitosamente.');
    }

    /**
     * Elimina una ruta de la base de datos.
     */
    public function destroy(Ruta $ruta)
    {
        // Opcional: Verificar si la ruta está siendo usada por algún viaje
        if ($ruta->viajes()->exists()) {
            return redirect()->route('admin.rutas.index')
                             ->with('error', 'No se puede eliminar la ruta porque está asociada a uno o más viajes.');
        }

        $ruta->delete();

        return redirect()->route('admin.rutas.index')
                         ->with('success', 'Ruta eliminada exitosamente.');
    }
}