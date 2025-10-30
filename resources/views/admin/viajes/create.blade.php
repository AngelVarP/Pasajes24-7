<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Nuevo Viaje - Admin</title>
    
    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        
        .admin-sidebar { background-color: #111827; } 
        .admin-sidebar a { transition: all 0.2s ease-in-out; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background-color: #374151; color: #f59e0b; } 
        .admin-sidebar a.active { border-left: 4px solid #f59e0b; padding-left: calc(1rem - 4px); font-weight: 600; } 

        .main-content-bg {
             background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 100%); 
        }
        /* Custom focus ring - Amber */
        input:focus, select:focus, button:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4); /* amber-500/40 */
            border-color: #f59e0b !important; /* amber-500 con !important para anular */
        }
        /* *** ESTILO DEL TÍTULO CON GRADIENTE (COPIADO DEL DASHBOARD) *** */
        .header-title {
             background: linear-gradient(to right, #f59e0b, #f97316); /* Gradiente ámbar/naranja */
             -webkit-background-clip: text;
             background-clip: text;
             color: transparent;
             font-weight: 800; /* Extra bold */
             letter-spacing: -0.025em; /* Ligeramente más junto */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">
        {{-- Sidebar (Igual que en el Dashboard) --}}
        <aside class="admin-sidebar w-64 text-gray-300 hidden md:flex md:flex-col fixed inset-y-0 z-50 shadow-lg">
            {{-- Logo --}}
            <div class="flex items-center justify-center h-20 border-b border-gray-700">
                 <a href="/">
                    <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-12 w-auto filter brightness-0 invert">
                </a>
            </div>
            {{-- Navegación --}}
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
                 <div class="pt-4 pb-2 px-4">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión Principal</span>
                </div>
                <a href="{{ route('admin.viajes.create') }}" class="active flex items-center px-4 py-2.5 rounded-lg text-sm font-medium"> 
                     <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Crear Viaje</span>
                </a>
                {{-- ... (otros enlaces del sidebar) ... --}}
                <a href="{{ route('admin.viajes.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo" class="h-5 w-auto mr-3 flex-shrink-0 filter brightness-0 invert">
                    <span>Gestionar Viajes</span>
                </a>
                <a href="{{ route('admin.rutas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                     <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Gestionar Rutas</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span>Gestionar Empresas</span>
                </a>
                <a href="{{ route('admin.ciudades.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.09M19.945 11H19a2 2 0 00-2 2v1a2 2 0 01-2 2 2 2 0 00-2 2v2.945M16 3.935V5.5A2.5 2.5 0 0113.5 8h-.09M12 21v-2.055A2.5 2.5 0 0114.5 16h-.09M12 3c-1.355 0-2.67.291-3.838.813a10.98 10.98 0 00-6.817 6.817C.791 11.33 0 12.645 0 14c0 1.355.791 2.67 1.345 3.838a10.98 10.98 0 006.817 6.817C9.33 24.209 10.645 25 12 25c1.355 0 2.67-.791 3.838-1.345a10.98 10.98 0 006.817-6.817C23.209 16.67 24 15.355 24 14c0-1.355-.791-2.67-1.345-3.838a10.98 10.98 0 00-6.817-6.817C14.67 2.791 13.355 2 12 2z"></path></svg>
                    <span>Gestionar Ciudades</span>
                </a>
                <a href="{{ route('admin.reservas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Ver Reservas</span>
                </a>
            </nav>
            {{-- Logout --}}
            <div class="mt-auto p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-900 hover:text-red-300 transition duration-150">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenido Principal --}}
        <div class="flex-1 flex flex-col md:ml-64">
            
            {{-- Header Superior --}}
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 border-b border-gray-200">
                 <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    {{-- Título de la sección actual (Crear Nuevo Viaje) --}}
                    <h1 class="header-title text-2xl font-extrabold tracking-tight">Crear Nuevo Viaje</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            {{-- Área de Contenido Principal del Formulario --}}
            <main class="flex-grow p-6 lg:p-10 main-content-bg">
                
                <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-4xl mx-auto"> 
                    {{-- Subtítulo con el icono (para crear consistencia) --}}
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-4 border-gray-200 flex items-center">
                         <svg class="w-6 h-6 mr-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         Añadir Detalles del Viaje
                    </h2>

                    {{-- Mensajes de Éxito y Error --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                            <p class="font-bold">Por favor, corrige los siguientes errores:</p>
                            <ul class="list-disc list-inside mt-2 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulario --}}
                    <form action="{{ route('admin.viajes.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Fila 1: Ruta y Empresa --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="ruta_id" class="block text-sm font-medium text-gray-700 mb-1">Ruta:</label>
                                <select name="ruta_id" id="ruta_id" required
                                        class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                                    <option value="">-- Selecciona una ruta --</option>
                                    @foreach ($rutas as $ruta)
                                        <option value="{{ $ruta->id }}" {{ old('ruta_id') == $ruta->id ? 'selected' : '' }}>
                                            {{ $ruta->origen->nombre }} &rarr; {{ $ruta->destino->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-1">Empresa:</label>
                                <select name="empresa_id" id="empresa_id" required
                                        class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                                    <option value="">-- Selecciona una empresa --</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Fila 2: Fecha y Hora --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fecha_salida" class="block text-sm font-medium text-gray-700 mb-1">Fecha Salida:</label>
                                <input type="date" name="fecha_salida" id="fecha_salida" required 
                                       value="{{ old('fecha_salida', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                       min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="hora_salida" class="block text-sm font-medium text-gray-700 mb-1">Hora Salida:</label>
                                <input type="time" name="hora_salida" id="hora_salida" required 
                                       value="{{ old('hora_salida', '08:00') }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                            </div>
                        </div>

                        {{-- Fila 3: Precio, Asientos, Tipo Servicio --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio (S/.):</label>
                                <input type="number" name="precio" id="precio" step="0.01" min="0" required 
                                       value="{{ old('precio') }}" placeholder="Ej: 50.00"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="asientos_totales" class="block text-sm font-medium text-gray-700 mb-1">Asientos Totales:</label>
                                <input type="number" name="asientos_totales" id="asientos_totales" min="1" required 
                                       value="{{ old('asientos_totales', 40) }}"
                                       class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="tipo_servicio" class="block text-sm font-medium text-gray-700 mb-1">Tipo Servicio:</label>
                                <select name="tipo_servicio" id="tipo_servicio" required
                                        class="block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                                    <option value="">-- Selecciona tipo --</option>
                                    <option value="Cama" {{ old('tipo_servicio') == 'Cama' ? 'selected' : '' }}>Cama</option>
                                    <option value="Semi-cama" {{ old('tipo_servicio') == 'Semi-cama' ? 'selected' : '' }}>Semi-cama</option>
                                    <option value="Estándar" {{ old('tipo_servicio') == 'Estándar' ? 'selected' : '' }}>Estándar</option>
                                    <option value="Cama VIP" {{ old('tipo_servicio') == 'Cama VIP' ? 'selected' : '' }}>Cama VIP</option>
                                </select>
                            </div>
                        </div>

                        {{-- Botón de envío --}}
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200 mt-8">
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 mr-4 transition duration-150">Cancelar</a>
                            <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-offset-2 focus:ring-amber-500 transition duration-200 transform hover:-translate-y-0.5">
                                Guardar Viaje
                            </button>
                        </div>
                    </form>
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