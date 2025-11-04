<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Título específico para crear ruta --}}
    <title>Crear Nueva Ruta - Admin</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Favicon --}}
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
         /* Estilo para borde de input con error (opcional pero útil) */
         .input-error {
             border-color: #ef4444 !important; /* red-500 */
         }
         select.input-error { /* Específico para select */
             border-color: #ef4444 !important;
         }
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
                    {{-- Título de la sección actual --}}
                    <h1 class="header-title text-xl font-bold text-gray-900">Crear Nueva Ruta</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            {{-- Área de Contenido Principal del Formulario --}}
            <main class="flex-grow p-6 lg:p-10 bg-white">

                {{-- Formulario agrupado en fondo slate-50 --}}
                <div class="bg-slate-50 p-6 md:p-8 rounded-lg border border-slate-200 w-full max-w-4xl mx-auto">
                    {{-- Subtítulo con icono --}}
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-4 border-gray-200 flex items-center">
                        {{-- Icono adaptado para Rutas --}}
                        <svg class="w-6 h-6 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Añadir Detalles de la Ruta
                    </h2>

                    {{-- Mensajes de �?xito y Error --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg" role="alert">
                            <p class="font-bold">Por favor, corrige los siguientes errores:</p>
                            <ul class="list-disc list-inside mt-2 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulario para Rutas --}}
                    <form action="{{ route('admin.rutas.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Fila 1: Origen y Destino --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="origen_id" class="block text-sm font-medium text-gray-700 mb-1">Ciudad de Origen:</label>
                                <select name="origen_id" id="origen_id" required
                                        class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm @error('origen_id') input-error @enderror">
                                    <option value="">-- Selecciona un origen --</option>
                                    @foreach ($ciudades as $ciudad)
                                        <option value="{{ $ciudad->id }}" {{ old('origen_id') == $ciudad->id ? 'selected' : '' }}>
                                            {{ $ciudad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('origen_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="destino_id" class="block text-sm font-medium text-gray-700 mb-1">Ciudad de Destino:</label>
                                <select name="destino_id" id="destino_id" required
                                        class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm @error('destino_id') input-error @enderror">
                                    <option value="">-- Selecciona un destino --</option>
                                    @foreach ($ciudades as $ciudad)
                                        <option value="{{ $ciudad->id }}" {{ old('destino_id') == $ciudad->id ? 'selected' : '' }}>
                                            {{ $ciudad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destino_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                             {{-- Mensajes de error específicos (misma lógica que antes) --}}
                             @error('origen_id')
                                @if ($message === 'El origen y el destino no pueden ser la misma ciudad.')
                                    <p class="text-red-500 text-xs md:col-span-2">{{ $message }}</p>
                                @endif
                                @if ($message === 'Ya existe una ruta con este origen y destino.')
                                    <p class="text-red-500 text-xs md:col-span-2">{{ $message }}</p>
                                @endif
                             @enderror
                        </div>

                        {{-- Fila 2: Duración Estimada --}}
                        <div>
                            <label for="duracion_estimada_minutos" class="block text-sm font-medium text-gray-700 mb-1">Duración Estimada (en minutos):</label>
                            <input type="number" name="duracion_estimada_minutos" id="duracion_estimada_minutos" required min="1" step="1"
                                   value="{{ old('duracion_estimada_minutos') }}" placeholder="Ej: 480"
                                   class="block w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm @error('duracion_estimada_minutos') input-error @enderror">
                            @error('duracion_estimada_minutos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Botón de envío --}}
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200 mt-8">
                            <a href="{{ route('admin.rutas.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 mr-4 transition duration-150">Cancelar</a>
                            <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg hover:from-orange-500 hover:to-orange-600 focus:outline-none focus:ring-offset-2 focus:ring-orange-400 transition duration-200 transform hover:-translate-y-0.5">
                                Guardar Ruta
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            {{-- Footer (Igual que en tu create de viajes) --}}
            <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
               &copy; {{ date('Y') }} Pasajes24/7 - Panel de Administración.
            </footer>
        </div>
    </div>

</body>
</html>


