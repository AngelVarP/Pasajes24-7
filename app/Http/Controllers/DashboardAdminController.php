<?php

namespace App\Http\Controllers;

use App\Models\EmpresaDeTransporte;
use App\Models\Viaje;
use App\Models\Ciudad; // <-- Nuevo
use App\Models\Ruta;   // <-- Nuevo
use Illuminate\Support\Carbon;

class DashboardAdminController extends Controller
{
    /**
     * Calcula y muestra las estadísticas clave para el Dashboard.
     */
    public function index()
    {
        // 1. Empresas Activas (Se mantiene)
        $empresasActivas = EmpresaDeTransporte::count();
        
        // 2. Viajes Hoy (Se mantiene)
        $viajesHoy = Viaje::whereDate('fecha_salida', Carbon::today())->count();
        
        // 3. 🚨 NUEVO: Ciudades Registradas (Reemplaza a Reservas Pendientes)
        $ciudadesRegistradas = Ciudad::count();
        
        // 4. 🚨 NUEVO: Rutas Totales (Reemplaza a Asientos Libres Hoy)
        $rutasTotales = Ruta::count();
        
        // Asientos Libres Hoy (Si quieres guardarlo para reportes futuros)
        // $asientosLibresHoy = Viaje::whereDate('fecha_salida', Carbon::today())->sum('asientos_disponibles');


        return view('admin.dashboard', compact(
            'empresasActivas', 
            'viajesHoy', 
            'ciudadesRegistradas', // <-- Nuevo
            'rutasTotales'         // <-- Nuevo
        ));
    }
}