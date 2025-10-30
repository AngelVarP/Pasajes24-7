<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Ruta; // Necesitas el modelo Ruta para llenar los filtros
use Illuminate\Http\Request;

class ReservaAdminController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener todas las rutas para llenar el selector de filtros
        $rutas = Ruta::with(['origen', 'destino'])->orderBy('origen_id')->get();

        // 2. Iniciar la consulta base (con Eager Loading, como ya lo tienes)
        $reservasQuery = Reserva::latest()->with([
            'viaje.ruta.origen',
            'viaje.ruta.destino',
            'viaje.empresa', 
            'reservaAsientos.asiento'
        ]);

        // 3. Aplicar Filtro por Ruta (por ID)
        if ($request->filled('ruta_id')) {
            $rutaId = $request->input('ruta_id');
            // Usamos whereHas para filtrar reservas que pertenezcan a viajes con esa ruta_id
            $reservasQuery->whereHas('viaje', function ($query) use ($rutaId) {
                $query->where('ruta_id', $rutaId);
            });
        }
        
        // 4. Aplicar Filtro por Estado
        if ($request->filled('estado')) {
            $reservasQuery->where('estado', $request->input('estado'));
        }

        // 5. Ejecutar la consulta y paginar
        $reservas = $reservasQuery->paginate(15)->withQueryString(); 
        // withQueryString() mantiene los filtros aplicados en la paginación

        // 6. Devolver la vista con las reservas y la lista de rutas para el filtro
        return view('admin.reservas.index', compact('reservas', 'rutas'));
    }
}