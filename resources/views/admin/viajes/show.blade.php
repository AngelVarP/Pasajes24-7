<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Detalle del Viaje #{{ $viaje->id }} - Admin</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
body { font-family: 'Inter', sans-serif; }
.admin-sidebar { background-color: #111827; }
.admin-sidebar a { transition: all 0.2s ease-in-out; }
.admin-sidebar a:hover, .admin-sidebar a.active { background-color: #374151; color: #f59e0b; }
.admin-sidebar a.active { border-left: 4px solid #f59e0b; padding-left: calc(1rem - 4px); font-weight: 600; }
.main-content-bg { background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 100%); }
.header-title { background: linear-gradient(to right, #f59e0b, #f97316); -webkit-background-clip: text; background-clip: text; color: transparent; font-weight: 800; letter-spacing: -0.025em; }
.timeline-dot { width: 10px; height: 10px; border-radius: 9999px; }
input:focus, select:focus, button:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.35); border-color: #f59e0b !important; }
</style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        <div class="flex-1 md:ml-64 main-content-bg min-h-screen flex flex-col">
            <header class="bg-white shadow-md px-6 py-6 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl header-title">Viaje #{{ $viaje->id }}</h1>
                    <p class="text-sm text-gray-500 mt-2 md:mt-1">Detalle de ruta, empresa y eventos registrados.</p>
                </div>
                <a href="{{ route('admin.viajes.index') }}" class="mt-3 md:mt-0 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Volver al listado
                </a>
            </header>

            <main class="flex-1 px-6 py-8 space-y-6">
                <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 col-span-2">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">InformaciÃ³n general</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <p class="text-gray-500 uppercase text-xs tracking-wide">Ruta</p>
                                <p class="font-semibold text-gray-900 mt-1">{{ optional($viaje->ruta->origen)->nombre ?? 'â€”' }} â†’ {{ optional($viaje->ruta->destino)->nombre ?? 'â€”' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase text-xs tracking-wide">Empresa</p>
                                <p class="font-semibold text-gray-900 mt-1">{{ $viaje->empresa->nombre ?? 'â€”' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase text-xs tracking-wide">Fecha y hora de salida</p>
                                <p class="font-semibold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($viaje->fecha_salida.' '.$viaje->hora_salida)->isoFormat('DD MMM YYYY - HH:mm') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase text-xs tracking-wide">Estado actual</p>
                                @php
                                    $estadoClass = [
                                        'programado' => 'bg-blue-100 text-blue-800',
                                        'en_curso' => 'bg-yellow-100 text-yellow-800',
                                        'en_retraso' => 'bg-orange-100 text-orange-800',
                                        'completado' => 'bg-green-100 text-green-800',
                                        'cancelado' => 'bg-red-100 text-red-800',
                                    ][$viaje->estado] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold mt-1 {{ $estadoClass }}">{{ str_replace('_', ' ', $viaje->estado) }}</span>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase text-xs tracking-wide">Llegada real</p>
                                <p class="font-semibold text-gray-900 mt-1">{{ optional($viaje->hora_llegada_real)->isoFormat('DD MMM YYYY - HH:mm') ?? 'â€”' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase text-xs tracking-wide">Retraso registrado</p>
                                <p class="font-semibold text-gray-900 mt-1">{{ $viaje->minutos_retraso !== null ? $viaje->minutos_retraso . ' min' : 'â€”' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Resumen</h2>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex justify-between">
                                <span>Asientos totales</span>
                                <span class="font-semibold text-gray-900">{{ $viaje->asientos_totales }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Asientos disponibles</span>
                                <span class="font-semibold text-gray-900">{{ $viaje->asientos_disponibles }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Servicio</span>
                                <span class="font-semibold text-gray-900 capitalize">{{ $viaje->tipo_servicio }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Precio base</span>
                                <span class="font-semibold text-gray-900">S/ {{ number_format($viaje->precio, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <section class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">Historial de eventos</h2>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">{{ $eventos->count() }} eventos registrados</span>
                    </div>
                    <div class="p-6">
                        @if ($eventos->isEmpty())
                            <p class="text-sm text-gray-500">AÃºn no se registran eventos para este viaje.</p>
                        @else
                        <ul class="space-y-6">
                            @foreach ($eventos as $evento)
                                <li class="relative pl-6">
                                    <span class="timeline-dot bg-amber-500 absolute left-0 top-1.5"></span>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800 capitalize">{{ str_replace('_', ' ', $evento->tipo_evento) }}</p>
                                            <p class="text-xs text-gray-500">{{ $evento->created_at->isoFormat('DD MMM YYYY - HH:mm') }} Â· Actor: {{ $evento->actor_tipo }}</p>
                                        </div>
                                        @if($evento->estado_nuevo)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $evento->estado_anterior ?? 'â€”' }} â†’ {{ $evento->estado_nuevo }}
                                            </span>
                                        @endif
                                    </div>
                                    @if(!empty($evento->detalles))
                                        <div class="mt-2 bg-gray-50 border border-gray-200 rounded-md px-4 py-3 text-xs text-gray-600 space-y-1">
                                            @foreach ($evento->detalles as $clave => $valor)
                                                <div class="flex justify-between">
                                                    <span class="font-semibold text-gray-700">{{ ucwords(str_replace('_', ' ', $clave)) }}</span>
                                                    <span>{{ is_array($valor) ? json_encode($valor) : $valor }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </section>
            </main>

            <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} Pasajes24/7 - Panel de AdministraciÃ³n.
            </footer>
        </div>
    </div>

</body>
</html>

