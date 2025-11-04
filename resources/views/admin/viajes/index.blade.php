<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Viajes - Admin</title>

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
    .table-cell-fixed { width: 100px; }
</style>
</head>
<body class="bg-white text-gray-900">

    <div class="flex min-h-screen">
        {{-- =============================================== --}}
        {{-- SIDEBAR (SLATE-100)                           --}}
        {{-- =============================================== --}}
        @include('admin.partials.sidebar')

        {{-- Contenido Principal --}}
        <div class="flex-1 flex flex-col md:ml-64">
            
            {{-- Header Superior --}}
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 border-b border-gray-200 sticky top-0 z-40">
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="header-title text-xl font-bold text-gray-900">Viajes</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            {{-- Área de Contenido Principal del Listado --}}
            <main class="flex-grow p-6 lg:p-10 bg-white">
                
                {{-- Encabezado y Botones --}}
                <div class="flex flex-wrap justify-between items-center mb-8 border-b pb-4 border-gray-200 gap-4">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Listado de Viajes Programados
                    </h2>

                    {{-- Contenedor para botones --}}
                    <div class="flex space-x-3">
                        {{-- Botón para Actualizar Estados (Botón Secundario) --}}
                        <form action="{{ route('admin.viajes.actualizarEstados') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-lg shadow-sm transition duration-200 flex items-center text-sm">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m-15.357-2a8.001 8.001 0 0015.058 2m0 0H15"></path></svg>
                                Actualizar Estados
                            </button>
                        </form>

                        {{-- Botón Crear Nuevo Viaje (Botón Primario Naranja Pastel) --}}
                        <a href="{{ route('admin.viajes.create') }}" 
                        class="bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg hover:from-orange-500 hover:to-orange-600 transition duration-200 transform hover:-translate-y-0.5 flex items-center text-sm">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Crear Nuevo Viaje
                        </a>
                    </div>
                </div>

                {{-- Mensajes de �?xito/Error --}}
                @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif
                @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif
                
                {{-- **FORMULARIO DE FILTROS (Fondo slate-50 para agrupar) ** --}}
                <div class="bg-slate-50 p-6 rounded-lg mb-6 border border-slate-200">
                    <form action="{{ route('admin.viajes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        
                        {{-- Filtro por Ruta --}}
                        <div>
                            <label for="ruta_id" class="block text-sm font-medium text-gray-700 mb-1">Ruta:</label>
                            <select name="ruta_id" id="ruta_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-400 focus:border-orange-400">
                                <option value="">-- Todas las Rutas --</option>
                                @foreach ($rutas as $ruta)
                                    <option value="{{ $ruta->id }}" 
                                        {{ request('ruta_id') == $ruta->id ? 'selected' : '' }}>
                                        {{ $ruta->origen->nombre }} &rarr; {{ $ruta->destino->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Filtro por Empresa --}}
                        <div>
                            <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-1">Empresa:</label>
                            <select name="empresa_id" id="empresa_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-400 focus:border-orange-400">
                                <option value="">-- Todas las Empresas --</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" 
                                        {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por Fecha de Salida --}}
                        <div>
                            <label for="fecha_salida" class="block text-sm font-medium text-gray-700 mb-1">Fecha Salida:</label>
                            <input type="date" name="fecha_salida" id="fecha_salida" 
                                   value="{{ request('fecha_salida') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-400 focus:border-orange-400">
                        </div>

                        {{-- Filtro por Estado --}}
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado:</label>
                            <select name="estado" id="estado" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-400 focus:border-orange-400">
                                <option value="">-- Todos los Estados --</option>
                                @foreach ($estadosDisponibles as $estado)
                                    <option value="{{ $estado }}" 
                                        {{ request('estado') == $estado ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $estado)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Botones de Acción (Minimalistas) --}}
                        <div class="flex space-x-2">
                            <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-700 transition duration-200 text-sm">
                                Filtrar
                            </button>
                            <a href="{{ route('admin.viajes.index') }}" class="bg-slate-200 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-slate-300 transition duration-200 text-sm">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
                {{-- **FIN FORMULARIO DE FILTROS** --}}


                {{-- Contenedor de la Tabla (Minimalista) --}}
                <div class="bg-white rounded-lg shadow-sm overflow-x-auto border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salida</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell-fixed">Precio (S/.)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asientos Disp.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Lógica para recorrer los viajes --}}
                            @forelse ($viajes as $viaje)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $viaje->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $viaje->ruta->origen->nombre }} &rarr; {{ $viaje->ruta->destino->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $viaje->empresa->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                        {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/M') }} a las {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold table-cell-fixed">{{ number_format($viaje->precio, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 table-cell-fixed">{{ $viaje->asientos_disponibles }} / {{ $viaje->asientos_totales }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 capitalize">{{ $viaje->tipo_servicio }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $estadoClass = [
                                                'programado' => 'bg-blue-100 text-blue-800',
                                                'en_curso' => 'bg-yellow-100 text-yellow-800',
                                                'en_retraso' => 'bg-orange-100 text-orange-800',
                                                'completado' => 'bg-green-100 text-green-800',
                                                'cancelado' => 'bg-red-100 text-red-800',
                                            ][$viaje->estado] ?? 'bg-gray-100 text-gray-800';
                                            $horaLlegadaFormateada = optional($viaje->hora_llegada_real)->format('d/M H:i');
                                            $textoRetraso = $viaje->minutos_retraso !== null ? $viaje->minutos_retraso . ' min' : '--';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClass }} capitalize">
                                            {{ str_replace('_', ' ', $viaje->estado) }}
                                        </span>
                                        <div class="mt-2 space-y-1 text-xs text-gray-500 leading-tight">
                                            <span class="block">Llegada: {{ $horaLlegadaFormateada ?? '--' }}</span>
                                            <span class="block">Retraso: {{ $textoRetraso }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @if ($viaje->estado === 'programado' )
                                            <a href="{{ route("admin.viajes.edit", $viaje) }}"
                                            class="text-orange-500 hover:text-orange-700 transition duration-150 ease-in-out">
                                            Editar
                                            </a>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed">Editar</span>
                                        @endif

                                        @if ($viaje->estado === 'programado' || $viaje->estado === 'en_curso')
                                            <form action="{{ route("admin.viajes.cancelar", $viaje) }}" method="POST" class="inline-block" onsubmit="return confirm('Estas seguro de que deseas cancelar este viaje?');">
                                                @csrf
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out focus:outline-none bg-transparent border-none p-0 cursor-pointer">
                                                    Cancelar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed">Cancelar</span>
                                        @endif

                                        @if (in_array($viaje->estado, ['en_curso', 'en_retraso']))
                                            <form action="{{ route("admin.viajes.finalizar", $viaje) }}" method="POST" class="inline-flex items-center space-x-2 mt-2">
                                                @csrf
                                                <input type="datetime-local"
                                                       name="hora_llegada_real"
                                                       value="{{ $viaje->hora_llegada_real ? $viaje->hora_llegada_real->format('Y-m-d\TH:i') : '' }}"
                                                       class="border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-800 transition duration-150 ease-in-out">
                                                    Confirmar llegada
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-10 text-center text-gray-500">No hay viajes programados que coincidan con los filtros. ¡Crea uno nuevo!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Links de Paginación --}}
                    @if ($viajes->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-white">
                        {{ $viajes->links() }}
                    </div>
                    @endif
                </div>
                
            </main>

            {{-- Footer (Igual que en Dashboard) --}}
            <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} Pasajes24/7 - Panel de Administración.
            </footer>
        </div>
    </div>

</body>
</html>


