<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PuntualidadReportController extends Controller
{
    public function index(Request $request)
    {
        $fechaFinInput = $request->input('fecha_fin');
        $fechaInicioInput = $request->input('fecha_inicio');

        $fechaFin = $fechaFinInput
            ? Carbon::parse($fechaFinInput)->endOfDay()
            : Carbon::now()->endOfDay();

        $fechaInicio = $fechaInicioInput
            ? Carbon::parse($fechaInicioInput)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        if ($fechaInicio->greaterThan($fechaFin)) {
            [$fechaInicio, $fechaFin] = [$fechaFin->copy()->subDays(7)->startOfDay(), $fechaFin];
        }

        $baseQuery = Viaje::query()
            ->whereBetween('fecha_salida', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->where('estado', 'completado');

        $totalCompletados = (clone $baseQuery)->count();

        $puntuales = (clone $baseQuery)
            ->where(function ($query) {
                $query->whereNull('minutos_retraso')
                      ->orWhere('minutos_retraso', 0);
            })
            ->count();

        $retrasoPromedio = (clone $baseQuery)
            ->whereNotNull('minutos_retraso')
            ->avg('minutos_retraso');

        $topRutas = (clone $baseQuery)
            ->whereNotNull('ruta_id')
            ->selectRaw('ruta_id, AVG(minutos_retraso) as retraso_promedio, COUNT(*) as total_viajes')
            ->groupBy('ruta_id')
            ->with(['ruta.origen', 'ruta.destino'])
            ->orderByDesc('retraso_promedio')
            ->take(5)
            ->get();

        $topEmpresas = (clone $baseQuery)
            ->whereNotNull('empresa_id')
            ->selectRaw('empresa_id, AVG(minutos_retraso) as retraso_promedio, COUNT(*) as total_viajes')
            ->groupBy('empresa_id')
            ->with('empresa')
            ->orderByDesc('retraso_promedio')
            ->take(5)
            ->get();

        $tendenciaPorDia = (clone $baseQuery)
            ->selectRaw('fecha_salida as fecha, COUNT(*) as total, SUM(CASE WHEN IFNULL(minutos_retraso, 0) > 0 THEN 1 ELSE 0 END) as retrasados')
            ->groupBy('fecha_salida')
            ->orderBy('fecha_salida')
            ->get();

        $tasaPuntualidad = $totalCompletados > 0
            ? round(($puntuales / $totalCompletados) * 100, 1)
            : null;

        $retrasoPromedio = $retrasoPromedio !== null
            ? round($retrasoPromedio, 1)
            : null;

        return view('admin.reportes.puntualidad', [
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'totalCompletados' => $totalCompletados,
            'puntuales' => $puntuales,
            'tasaPuntualidad' => $tasaPuntualidad,
            'retrasoPromedio' => $retrasoPromedio,
            'topRutas' => $topRutas,
            'topEmpresas' => $topEmpresas,
            'tendenciaPorDia' => $tendenciaPorDia,
        ]);
    }
}
