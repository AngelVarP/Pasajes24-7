<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Ruta; 
use App\Models\EmpresaDeTransporte; // <-- Importante
use Illuminate\Http\Request;

class ReservaAdminController extends Controller
{
    /**
     * Muestra una lista de todas las reservas, con filtros por Ruta, Fecha de Salida y Empresa.
     */
    public function index(Request $request)
    {
        // 1. Obtener datos auxiliares para los filtros
        $rutas = Ruta::with(['origen', 'destino'])->orderBy('origen_id')->get();
        $empresas = EmpresaDeTransporte::orderBy('nombre')->get(); // <-- Obtenemos las empresas

        // 2. Iniciar la consulta base (con Eager Loading)
        $reservasQuery = Reserva::latest()->with([
            'viaje.ruta.origen',
            'viaje.ruta.destino',
            'viaje.empresa', 
            'reservaAsientos.asiento'
        ]);

        // 3. Aplicar Filtro por Ruta
        if ($request->filled('ruta_id')) {
            $rutaId = $request->input('ruta_id');
            $reservasQuery->whereHas('viaje', function ($query) use ($rutaId) {
                $query->where('ruta_id', $rutaId);
            });
        }
        
        // 4. Aplicar Filtro por Fecha de Salida
        if ($request->filled('fecha_salida')) {
            $fechaSalida = $request->input('fecha_salida');
            $reservasQuery->whereHas('viaje', function ($query) use ($fechaSalida) {
                $query->whereDate('fecha_salida', $fechaSalida);
            });
        }
        
        // 5. Aplicar Filtro por Empresa
        if ($request->filled('empresa_id')) {
            $empresaId = $request->input('empresa_id');
            // whereHas en Viaje para filtrar la empresa
            $reservasQuery->whereHas('viaje', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            });
        }

        // 6. Ejecutar la consulta y paginar
        $reservas = $reservasQuery->paginate(15)->withQueryString(); 

        // 7. Devolver la vista
        return view('admin.reservas.index', compact('reservas', 'rutas', 'empresas'));
    }
}