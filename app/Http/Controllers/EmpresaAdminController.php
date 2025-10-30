<?php

namespace App\Http\Controllers;

use App\Models\EmpresaDeTransporte;
use Illuminate\Http\Request;

class EmpresaAdminController extends Controller
{
    /**
     * Muestra la lista paginada de empresas de transporte.
     */
    public function index()
    {
        $empresas = EmpresaDeTransporte::orderBy('nombre')->paginate(15);
        return view('admin.empresas.index', compact('empresas'));
    }

    /**
     * Muestra el formulario para crear una nueva empresa.
     */
    public function create()
    {
        return view('admin.empresas.create');
    }

    /**
     * Guarda una nueva empresa en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:empresas_de_transporte,nombre',
            'ruc' => 'required|string|max:15|unique:empresas_de_transporte,ruc',
            // CORREGIDO: Eliminamos la restricción '|url' para aceptar cualquier string o nulo.
            'logo_url' => 'nullable|string|max:255', 
            'email_contacto' => 'nullable|email|max:100',
            'telefono_contacto' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'El nombre de la empresa es obligatorio.',
            'nombre.unique' => 'Ya existe una empresa con este nombre.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.unique' => 'Ya existe una empresa con este RUC.',
        ]);

        EmpresaDeTransporte::create($request->all());

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa de transporte creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una empresa existente.
     */
    public function edit(EmpresaDeTransporte $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

    /**
     * Actualiza una empresa existente en la base de datos.
     */
    public function update(Request $request, EmpresaDeTransporte $empresa)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:empresas_de_transporte,nombre,' . $empresa->id,
            'ruc' => 'required|string|max:15|unique:empresas_de_transporte,ruc,' . $empresa->id,
            // CORREGIDO: Eliminamos la restricción '|url' para aceptar cualquier string o nulo.
            'logo_url' => 'nullable|string|max:255', 
            'email_contacto' => 'nullable|email|max:100',
            'telefono_contacto' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'El nombre de la empresa es obligatorio.',
            'nombre.unique' => 'Ya existe una empresa con este nombre.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.unique' => 'Ya existe una empresa con este RUC.',
        ]);

        $empresa->update($request->all());

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa de transporte actualizada exitosamente.');
    }

    /**
     * Elimina una empresa de la base de datos.
     */
    public function destroy(EmpresaDeTransporte $empresa)
    {
        // CRÍTICO: Verificar integridad referencial (si tiene viajes, no se elimina)
        if ($empresa->viajes()->exists()) {
            return redirect()->route('admin.empresas.index')
                             ->with('error', 'No se puede eliminar la empresa porque está asociada a uno o más viajes programados.');
        }

        $empresa->delete();

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa de transporte eliminada exitosamente.');
    }
}