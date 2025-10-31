<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reporte de Reservas - Admin</title>

{{-- Estilos --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- =============================================== --}}
{{-- ESTILOS PARA EL DASHBOARD "MINIMALISTA PASTEL"  --}}
{{-- =============================================== --}}
<style>
    body { 
        font-family: 'Inter', sans-serif; 
        -webkit-font-smoothing: antialiased; 
        -moz-osx-font-smoothing: grayscale;
        background-color: #ffffff; /* Fondo principal blanco */
    }

    /* Sidebar: Un gris/azul sutil */
    .admin-sidebar { 
        background-color: #f1f5f9; /* bg-slate-100 */
        border-right: 1px solid #e5e7eb; /* border-r border-gray-200 */
    }
    
    /* Enlaces del Sidebar: Texto gris */
    .admin-sidebar a { 
        color: #4b5563; /* text-gray-600 */
        transition: all 0.2s ease-in-out;
    }
    .admin-sidebar a:hover { 
        background-color: #ffffff; /* bg-white */
        color: #111827; /* text-gray-900 */
    }
    
    /* Enlace Activo: Fondo blanco + acento naranja pastel */
    .admin-sidebar a.active { 
        color: #fb923c; /* text-orange-400 */
        background-color: #ffffff; /* bg-white */
        border-left: 4px solid #fb923c; /* border-l-4 border-orange-400 */
        padding-left: calc(1rem - 4px);
        font-weight: 600;
    }

    /* Título del Header limpio */
    .header-title {
         color: #111827; /* text-gray-900 */
         font-weight: 800;
         letter-spacing: -0.025em;
    }

    /* Estilos de Focus con Naranja Pastel */
    input:focus, select:focus, button:focus-visible { 
        outline: none; 
        box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.4); /* orange-400/40 */
        border-color: #fb923c !important; /* orange-400 */
    }
</style>
</head>
<body class="bg-white text-gray-900">

<div class="flex min-h-screen">
    {{-- =============================================== --}}
    {{-- SIDEBAR (SLATE-100)                           --}}
    {{-- =============================================== --}}
    <aside class="admin-sidebar w-64 text-gray-600 hidden md:flex md:flex-col fixed inset-y-0 z-50">
        {{-- Logo --}}
        <div class="flex items-center justify-center h-20 border-b border-slate-200">
            <a href="/"><img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-20 w-auto"></a>
        </div>
        {{-- Menú --}}
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg><span>Dashboard</span></a>
            <div class="pt-4 pb-2 px-4"><span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestión</span></div>
            <a href="{{ route('admin.viajes.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.viajes.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                <span>Viajes</span>
            </a>
            <a href="{{ route('admin.rutas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.rutas.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Rutas</span>
            </a>
            <a href="{{ route('admin.empresas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.empresas.*') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg><span>Empresas</span></a>
            <a href="{{ route('admin.ciudades.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.ciudades.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.09M19.945 11H19a2 2 0 00-2 2v1a2 2 0 01-2 2 2 2 0 00-2 2v2.945M16 3.935V5.5A2.5 2.5 0 0113.5 8h-.09M12 21v-2.055A2.5 2.5 0 0114.5 16h-.09M12 3c-1.355 0-2.67.291-3.838.813a10.98 10.98 0 00-6.817 6.817C.791 11.33 0 12.645 0 14c0 1.355.791 2.67 1.345 3.838a10.98 10.98 0 006.817 6.817C9.33 24.209 10.645 25 12 25c1.355 0 2.67-.791 3.838-1.345a10.98 10.98 0 006.817-6.817C23.209 16.67 24 15.355 24 14c0-1.355-.791-2.67-1.345-3.838a10.98 10.98 0 00-6.817-6.817C14.67 2.791 13.355 2 12 2z"></path></svg><span>Ciudades</span>
            </a>
            <a href="{{ route('admin.reservas.index') }}" class="active flex items-center px-4 py-2.5 rounded-lg text-sm font-medium">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><span>Ver Reservas</span>
            </a>
        </nav>
        {{-- Logout --}}
        <div class="mt-auto p-4 border-t border-slate-200">
            <form method="POST" action="{{ route('admin.logout') }}"><button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-white hover:text-red-500 transition duration-150"><svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>Cerrar Sesión</button></form>
        </div>
    </aside>

    {{-- Contenido Principal --}}
    <div class="flex-1 flex flex-col md:ml-64">

        {{-- Header Superior --}}
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 border-b border-gray-200 sticky top-0 z-40">
            <div class="flex items-center">
                <button class="md:hidden mr-4 text-gray-500 hover:text-gray-700"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                <h1 class="header-title text-xl font-bold text-gray-900">Reporte de Reservas</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </header>

        {{-- Área de Contenido Principal del Listado --}}
        <main class="flex-grow p-6 lg:p-10 bg-white">

            {{-- Encabezado sin botón Crear (Reporte) --}}
            <div class="flex justify-between items-center mb-8 border-b pb-4 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v2m-1-8h2m-1 0V8m-1 4h2m-1 0h-2m-1-4h2m-1 0V8m8 0a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V17"></path></svg>
                    Listado de Reservas Registradas
                </h2>
            </div>

            {{-- **FORMULARIO DE FILTROS** (Fondo slate-50 para agrupar) --}}
            <div class="bg-slate-50 p-6 rounded-lg mb-6 border border-slate-200">
                <form action="{{ route('admin.reservas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    
                    {{-- Filtro por Ruta --}}
                    <div>
                        <label for="ruta_id" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Ruta:</label>
                        <select name="ruta_id" id="ruta_id" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-400 focus:border-orange-400">
                            <option value="">-- Todas las Rutas --</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id }}" 
                                    {{ request('ruta_id') == $ruta->id ? 'selected' : '' }}>
                                    {{ $ruta->origen->nombre }} &rarr; {{ $ruta->destino->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro por Fecha de Salida --}}
                    <div>
                        <label for="fecha_salida" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Salida:</label>
                        <input type="date" name="fecha_salida" id="fecha_salida" 
                               value="{{ request('fecha_salida') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-400 focus:border-orange-400">
                    </div>

                    {{-- Filtro por Empresa --}}
                    <div>
                        <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Empresa:</label>
                        <select name="empresa_id" id="empresa_id" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-400 focus:border-orange-400">
                            <option value="">-- Todas las Empresas --</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}" 
                                    {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                    {{ $empresa->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botones de Acción (Minimalistas) --}}
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-700 transition duration-200 text-sm">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.reservas.index') }}" class="bg-slate-200 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-slate-300 transition duration-200 text-sm">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
            {{-- **FIN FORMULARIO DE FILTROS** --}}


            {{-- Contenedor de la Tabla --}}
            <div class="bg-white rounded-lg shadow-sm overflow-x-auto border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Viaje / Ruta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Comprador</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Asientos / Pasajeros</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Fecha Reserva</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($reservas as $reserva)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-500">{{ $reserva->codigo_reserva }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-medium">{{ $reserva->viaje->ruta->origen->nombre }} &rarr; {{ $reserva->viaje->ruta->destino->nombre }}</span>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($reserva->viaje->hora_salida)->format('H:i') }} | {{ $reserva->viaje->empresa->nombre }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reserva->nombre_comprador }}
                                <div class="text-xs text-gray-500">{{ $reserva->email_comprador }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @php
                                    // Extrae y une los números de asiento de la relación N:M
                                    $asientos = $reserva->reservaAsientos->map(fn($ra) => $ra->asiento->numero)->implode(', ');
                                @endphp
                                <span class="font-medium">{{ $reserva->reservaAsientos->count() }} asiento(s)</span>
                                <div class="text-xs text-gray-500">Asientos: {{ $asientos }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 text-right">
                                S/ {{ number_format($reserva->monto_total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reserva->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @php
                                    // Mapeo de clases para evitar conflictos (solución a errores de linter)
                                    $estadoClass = [
                                        'pagado' => 'bg-green-100 text-green-800',
                                        'cancelado' => 'bg-red-100 text-red-800',
                                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                    $claseFinal = $estadoClass[$reserva->estado] ?? $estadoClass['pendiente'];
                                @endphp
                                
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $claseFinal }} capitalize">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">No hay reservas registradas que coincidan con los filtros.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Links de Paginación --}}
                @if ($reservas->hasPages())
                <div class="p-4 border-t border-gray-200 bg-white">
                    {{ $reservas->appends(request()->query())->links() }} {{-- Añadido appends para mantener filtros --}}
                </div>
                @endif
            </div>

        </main>

        {{-- Footer --}}
        <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
            &copy; {{ date('Y') }} Pasajes24/7 - Panel de Administración.
        </footer>
    </div>
</div>

</body>
</html>