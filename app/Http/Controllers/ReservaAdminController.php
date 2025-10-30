<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaAdminController extends Controller
{
    /**
     * Muestra una lista de todas las reservas.
     */
    public function index()
    {
        // 1. Obtener todas las reservas paginadas
        // Eager loading para optimizar: viaje, empresa, origen, destino y asientos
        $reservas = Reserva::latest()
            ->with([
                'viaje.ruta.origen',
                'viaje.ruta.destino',
                'viaje.empresa',
                'reservaAsientos.asiento'
            ])
            ->paginate(15); // Paginación para manejar grandes volúmenes de datos

        return view('admin.reservas.index', compact('reservas'));
    }

    // Como es un panel de reportes, normalmente no necesitas métodos store/update/destroy.
    // Solo si se requiere "cancelar" una reserva, se crearía un método 'cancelar' o se usaría 'update'.
}