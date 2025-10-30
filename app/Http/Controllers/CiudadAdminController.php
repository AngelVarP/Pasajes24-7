<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadAdminController extends Controller
{
    /**
     * Muestra la lista de ciudades.
     */
    public function index()
    {
        $ciudades = Ciudad::orderBy('nombre')->paginate(15);
        return view('admin.ciudades.index', compact('ciudades'));
    }

    /**
     * Muestra el formulario para crear una nueva ciudad.
     */
    public function create()
    {
        return view('admin.ciudades.create');
    }

    /**
     * Guarda una nueva ciudad en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:ciudades,nombre',
            'departamento' => 'required|string|max:100', // <<-- CAMBIO
        ], [
            'nombre.required' => 'El nombre de la ciudad es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.unique' => 'Ya existe una ciudad con este nombre.',
            'departamento.required' => 'El campo departamento es obligatorio.', // <<-- CAMBIO
            'departamento.max' => 'El departamento no puede exceder los 100 caracteres.',
        ]);

        Ciudad::create($request->all());

        return redirect()->route('admin.ciudades.index')
                         ->with('success', 'Ciudad creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una ciudad existente.
     */
    public function edit(Ciudad $ciudad)
    {
        return view('admin.ciudades.edit', compact('ciudad'));
    }

    /**
     * Actualiza una ciudad existente en la base de datos.
     */
    public function update(Request $request, Ciudad $ciudad)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:ciudades,nombre,' . $ciudad->id,
            'departamento' => 'required|string|max:100', // <<-- CAMBIO
        ], [
            'nombre.required' => 'El nombre de la ciudad es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.unique' => 'Ya existe otra ciudad con este nombre.',
            'departamento.required' => 'El campo departamento es obligatorio.', // <<-- CAMBIO
            'departamento.max' => 'El departamento no puede exceder los 100 caracteres.',
        ]);

        $ciudad->update($request->all());

        return redirect()->route('admin.ciudades.index')
                         ->with('success', 'Ciudad actualizada exitosamente.');
    }

    /**
     * Elimina una ciudad de la base de datos.
     */
    public function destroy(Ciudad $ciudad)
    {
        if ($ciudad->rutasOrigen()->exists() || $ciudad->rutasDestino()->exists()) {
            return redirect()->route('admin.ciudades.index')
                             ->with('error', 'No se puede eliminar la ciudad porque está asociada a una o más rutas.');
        }

        $ciudad->delete();

        return redirect()->route('admin.ciudades.index')
                         ->with('success', 'Ciudad eliminada exitosamente.');
    }
}