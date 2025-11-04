<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rutas - Admin</title>

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
        .table-cell-fixed-duracion { width: 120px; }
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
                    <h1 class="header-title text-xl font-bold text-gray-900">Rutas</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            {{-- Área de Contenido Principal del Listado --}}
            <main class="flex-grow p-6 lg:p-10 bg-white">

                {{-- Encabezado y Botón Crear --}}
                <div class="flex justify-between items-center mb-8 border-b pb-4 border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Listado de Rutas Registradas
                    </h2>
                    <a href="{{ route('admin.rutas.create') }}"
                       class="bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg hover:from-orange-500 hover:to-orange-600 transition duration-200 transform hover:-translate-y-0.5 flex items-center text-sm">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Añadir Nueva Ruta
                    </a>
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

                {{-- Contenedor de la Tabla --}}
                <div class="bg-white rounded-lg shadow-sm overflow-x-auto border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destino</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell-fixed-duracion">Duración (min)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($rutas as $ruta)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ruta->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $ruta->origen->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $ruta->destino->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700 table-cell-fixed-duracion">{{ $ruta->duracion_estimada_minutos }} min</td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <a href="{{ route('admin.rutas.edit', $ruta) }}"
                                           class="text-orange-500 hover:text-orange-700 transition duration-150 ease-in-out">
                                           Editar
                                        </a>

                                        {{-- ========================================================= --}}
                                        {{-- INICIO: CORRECCI�"N DEL onsubmit --}}
                                        {{-- ========================================================= --}}
                                        <form action="{{ route('admin.rutas.destroy', $ruta) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar la ruta?\nOrigen: {{ addslashes($ruta->origen->nombre ?? 'N/A') }}\nDestino: {{ addslashes($ruta->destino->nombre ?? 'N/A') }}\n\n¡Esta acción no se puede deshacer!');">
                                        {{-- ========================================================= --}}
                                        {{-- FIN: CORRECCI�"N DEL onsubmit --}}
                                        {{-- ========================================================= --}}
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out focus:outline-none bg-transparent border-none p-0 cursor-pointer">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">No hay rutas registradas. ¡Crea una nueva!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Links de Paginación --}}
                    @if ($rutas->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-white">
                        {{ $rutas->links() }}
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


