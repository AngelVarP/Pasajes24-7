<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Puntualidad - Admin</title>

{{-- Estilos --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    body {
        font-family: 'Inter', sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        background-color: #ffffff;
    }

    .admin-sidebar {
        background-color: #f1f5f9;
        border-right: 1px solid #e5e7eb;
    }

    .admin-sidebar a {
        color: #4b5563;
        transition: all 0.2s ease-in-out;
    }

    .admin-sidebar a:hover {
        background-color: #ffffff;
        color: #111827;
    }

    .admin-sidebar a.active {
        color: #fb923c;
        background-color: #ffffff;
        border-left: 4px solid #fb923c;
        padding-left: calc(1rem - 4px);
        font-weight: 600;
    }

    .header-title {
        color: #111827;
        font-weight: 800;
        letter-spacing: -0.025em;
    }

    input:focus,
    select:focus,
    button:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.4);
        border-color: #fb923c !important;
    }

    .metric-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 10px 25px -14px rgba(15, 23, 42, 0.25);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 30px -16px rgba(15, 23, 42, 0.3);
    }

    .badge-soft {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.725rem;
        font-weight: 600;
        background-color: #f1f5f9;
        color: #475569;
    }
</style>
</head>
<body class="bg-white text-gray-900">

    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col md:ml-64">

            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 border-b border-gray-200 sticky top-0 z-40">
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h1 class="header-title text-xl font-bold text-gray-900">Reporte de puntualidad</h1>
                        <p class="hidden md:block text-sm text-gray-500">Analiza el desempeño de rutas y empresas entre {{ $fechaInicio->isoFormat('DD MMM YYYY') }} y {{ $fechaFin->isoFormat('DD MMM YYYY') }}.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            <main class="flex-grow p-6 lg:p-10 bg-white">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-9 4h8m5-9V5a2 2 0 00-2-2h-3.17a2 2 0 01-1.414-.586l-.828-.828A2 2 0 0011.172 1H8a2 2 0 00-2 2v2"></path></svg>
                        Filtros de periodo
                    </h2>
                    <form method="GET" action="{{ route('admin.reportes.puntualidad') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaInicio->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" value="{{ $fechaFin->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div class="md:col-span-2 flex flex-wrap gap-3">
                            <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 text-white font-semibold px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg hover:from-orange-500 hover:to-orange-600 transition duration-200 transform hover:-translate-y-0.5 text-sm">
                                Actualizar
                            </button>
                            <a href="{{ route('admin.reportes.puntualidad') }}" class="bg-slate-100 text-slate-700 font-semibold px-5 py-2.5 rounded-lg shadow-sm hover:bg-slate-200 transition duration-200 text-sm">
                                Últimos 30 días
                            </a>
                        </div>
                    </form>
                </div>

            <section class="mb-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="metric-card p-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Viajes completados</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalCompletados }}</p>
                    </div>
                    <div class="metric-card p-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Viajes puntuales</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $puntuales }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $totalCompletados ? number_format(($puntuales / max($totalCompletados, 1)) * 100, 1) : 0 }}% del total</p>
                    </div>
                    <div class="metric-card p-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tasa de puntualidad</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $tasaPuntualidad !== null ? $tasaPuntualidad . '%' : 'â€”' }}</p>
                    </div>
                    <div class="metric-card p-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Retraso promedio</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $retrasoPromedio !== null ? $retrasoPromedio . ' min' : 'â€”' }}</p>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Rutas con mayor retraso</h3>
                            <p class="text-xs text-gray-500 mt-1">Promedio de minutos por ruta durante el periodo seleccionado.</p>
                        </div>
                        <span class="badge-soft">Top 5</span>
                    </div>
                    <div class="p-6">
                        @if ($topRutas->isEmpty())
                            <p class="text-sm text-gray-500">No hay datos suficientes en el periodo seleccionado.</p>
                        @else
                            <ul class="space-y-4">
                                @foreach ($topRutas as $item)
                                    <li class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ optional($item->ruta->origen)->nombre ?? 'â€”' }} -> {{ optional($item->ruta->destino)->nombre ?? 'â€”' }}
                                            </p>
                                            <p class="text-xs text-gray-500">Viajes analizados: {{ $item->total_viajes }}</p>
                                        </div>
                                        <span class="text-sm font-bold text-rose-500">{{ number_format($item->retraso_promedio, 1) }} min</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Empresas con mayor retraso</h3>
                            <p class="text-xs text-gray-500 mt-1">Promedio en minutos por operador.</p>
                        </div>
                        <span class="badge-soft">Top 5</span>
                    </div>
                    <div class="p-6">
                        @if ($topEmpresas->isEmpty())
                            <p class="text-sm text-gray-500">No hay datos suficientes en el periodo seleccionado.</p>
                        @else
                            <ul class="space-y-4">
                                @foreach ($topEmpresas as $item)
                                    <li class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ optional($item->empresa)->nombre ?? 'â€”' }}</p>
                                            <p class="text-xs text-gray-500">Viajes analizados: {{ $item->total_viajes }}</p>
                                        </div>
                                        <span class="text-sm font-bold text-rose-500">{{ number_format($item->retraso_promedio, 1) }} min</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </section>

            <section class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Tendencia diaria</h3>
                        <p class="text-xs text-gray-500 mt-1">Comparativo de viajes totales vs. retrasados por fecha de salida.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completados</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Retrasados</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-600">
                            @forelse ($tendenciaPorDia as $fila)
                                <tr>
                                    <td class="px-6 py-3">{{ \Carbon\Carbon::parse($fila->fecha)->isoFormat('DD MMM YYYY') }}</td>
                                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $fila->total }}</td>
                                    <td class="px-6 py-3">{{ $fila->retrasados }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-5 text-center text-gray-500">Sin datos para el rango seleccionado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
            &copy; {{ date('Y') }} Pasajes24/7 - Panel de Administración.
        </footer>
        </div>
    </div>

</body>
</html>


