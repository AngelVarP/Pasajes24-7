<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Administración - Pasajes24/7</title>

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
        .admin-sidebar a.active { border-left: 4px solid #f59e0b; padding-left: calc(1rem - 4px); font-weight: 600; } /* Más grueso el texto activo */

        .admin-card { transition: all 0.3s ease-out; }
        .admin-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .main-content-bg {
             background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        .stat-card { animation: popIn 0.5s ease-out backwards; }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        /* Estilo para el título del Header */
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
        {{-- Sidebar (Barra Lateral Izquierda) --}}
        <aside class="admin-sidebar w-64 text-gray-300 hidden md:flex md:flex-col fixed inset-y-0 z-50 shadow-lg">
            {{-- Logo --}}
            <div class="flex items-center justify-center h-20 border-b border-gray-700">
                 <a href="/">
                    <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-12 w-auto filter brightness-0 invert">
                </a>
            </div>

            {{-- Navegación del Admin --}}
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto"> {{-- Añadido overflow-y-auto --}}
                <a href="{{ route('admin.dashboard') }}" class="active flex items-center px-4 py-2.5 rounded-lg text-sm font-medium">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>

                {{-- Separador (Opcional) --}}
                <div class="pt-4 pb-2 px-4">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión Principal</span>
                </div>

                {{-- *** ENLACES DE ACCESO RÁPIDO AHORA EN SIDEBAR *** --}}
                <a href="{{ route('admin.viajes.create') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                     <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Crear Viaje</span>
                </a>
                <a href="{{ route('admin.viajes.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span>Gestionar Viajes</span>
                </a>
                 <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                     <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Gestionar Rutas</span>
                </a>
                 <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span>Gestionar Empresas</span>
                </a>
                 <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.09M19.945 11H19a2 2 0 00-2 2v1a2 2 0 01-2 2 2 2 0 00-2 2v2.945M16 3.935V5.5A2.5 2.5 0 0113.5 8h-.09M12 21v-2.055A2.5 2.5 0 0114.5 16h-.09M12 3c-1.355 0-2.67.291-3.838.813a10.98 10.98 0 00-6.817 6.817C.791 11.33 0 12.645 0 14c0 1.355.791 2.67 1.345 3.838a10.98 10.98 0 006.817 6.817C9.33 24.209 10.645 25 12 25c1.355 0 2.67-.791 3.838-1.345a10.98 10.98 0 006.817-6.817C23.209 16.67 24 15.355 24 14c0-1.355-.791-2.67-1.345-3.838a10.98 10.98 0 00-6.817-6.817C14.67 2.791 13.355 2 12 2z"></path></svg>
                    <span>Gestionar Ciudades</span>
                </a>
                 <a href="#" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Ver Reservas</span>
                </a>
                {{-- *** FIN ENLACES ACCESO RÁPIDO *** --}}
            </nav>

            {{-- Logout (Igual que antes) --}}
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
                    {{-- **** TÍTULO MEJORADO **** --}}
                    <h1 class="header-title text-2xl font-extrabold tracking-tight">Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            {{-- Área de Contenido Principal --}}
            <main class="flex-grow p-6 lg:p-10 main-content-bg">

                {{-- Bienvenida --}}
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">¡Bienvenido de vuelta, <span class="text-amber-600">{{ Auth::user()->name ?? 'Admin' }}</span>! 👋</h2>
                    <p class="text-gray-600 mt-1">Aquí tienes un resumen rápido de la actividad.</p>
                </div>

                {{-- Tarjetas de Estadísticas (Igual que antes) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
                    {{-- ... (código de las 4 tarjetas de estadísticas) ... --}}
                    <div class="stat-card bg-white p-5 rounded-lg shadow border-l-4 border-blue-500" style="animation-delay: 0.1s;"><div class="flex items-center"><div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg></div><div><p class="text-sm text-gray-500 font-medium">Viajes Hoy</p><p class="text-2xl font-bold text-gray-900">12</p></div></div></div>
                    <div class="stat-card bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500" style="animation-delay: 0.2s;"><div class="flex items-center"><div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div><p class="text-sm text-gray-500 font-medium">Reservas Pendientes</p><p class="text-2xl font-bold text-gray-900">3</p></div></div></div>
                    <div class="stat-card bg-white p-5 rounded-lg shadow border-l-4 border-green-500" style="animation-delay: 0.3s;"><div class="flex items-center"><div class="p-3 rounded-full bg-green-100 text-green-600 mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div><div><p class="text-sm text-gray-500 font-medium">Asientos Libres Hoy</p><p class="text-2xl font-bold text-gray-900">245</p></div></div></div>
                    <div class="stat-card bg-white p-5 rounded-lg shadow border-l-4 border-purple-500" style="animation-delay: 0.4s;"><div class="flex items-center"><div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div><div><p class="text-sm text-gray-500 font-medium">Empresas Activas</p><p class="text-2xl font-bold text-gray-900">3</p></div></div></div>
                </div>

                {{-- Accesos Rápidos (Cards Grandes - Igual que antes) --}}
                <h3 class="text-xl font-semibold text-gray-700 mb-6">Accesos Rápidos</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    {{-- ... (código de las 6 cards de acceso rápido) ... --}}
                    <a href="{{ route('admin.viajes.create') }}" class="admin-card flex flex-col p-6 bg-white rounded-xl shadow-lg border-b-4 border-amber-500 text-center items-center"><div class="p-4 rounded-full bg-amber-100 text-amber-600 mb-4 inline-block"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><h2 class="text-lg font-semibold text-gray-800 mb-1">Crear Viaje</h2><p class="text-gray-500 text-xs">Añadir nuevo horario.</p></a>
                    {{-- Card Gestionar Viajes --}}
                    <a href="{{ route('admin.viajes.index') }}" 
                    class="admin-card flex flex-col p-6 bg-white rounded-xl shadow-lg border-b-4 border-blue-500 text-center items-center">
                        <div class="p-4 rounded-full bg-blue-100 text-blue-600 mb-4 inline-block">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-1">Gestionar Viajes</h2>
                        <p class="text-gray-500 text-xs">Ver, editar o cancelar viajes.</p>
                    </a>
                    <a href="#" class="admin-card flex flex-col p-6 bg-white rounded-xl shadow-lg border-b-4 border-green-500 text-center items-center"><div class="p-4 rounded-full bg-green-100 text-green-600 mb-4 inline-block"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div><h2 class="text-lg font-semibold text-gray-800 mb-1">Gestionar Rutas</h2><p class="text-gray-500 text-xs">Editar orígenes/destinos.</p></a>
                    <a href="#" class="admin-card flex flex-col p-6 bg-white rounded-xl shadow-lg border-b-4 border-purple-500 text-center items-center"><div class="p-4 rounded-full bg-purple-100 text-purple-600 mb-4 inline-block"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div><h2 class="text-lg font-semibold text-gray-800 mb-1">Gestionar Empresas</h2><p class="text-gray-500 text-xs">Administrar transportistas.</p></a>
                    <a href="#" class="admin-card flex flex-col p-6 bg-white rounded-xl shadow-lg border-b-4 border-pink-500 text-center items-center"><div class="p-4 rounded-full bg-pink-100 text-pink-600 mb-4 inline-block"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.09M19.945 11H19a2 2 0 00-2 2v1a2 2 0 01-2 2 2 2 0 00-2 2v2.945M16 3.935V5.5A2.5 2.5 0 0113.5 8h-.09M12 21v-2.055A2.5 2.5 0 0114.5 16h-.09M12 3c-1.355 0-2.67.291-3.838.813a10.98 10.98 0 00-6.817 6.817C.791 11.33 0 12.645 0 14c0 1.355.791 2.67 1.345 3.838a10.98 10.98 0 006.817 6.817C9.33 24.209 10.645 25 12 25c1.355 0 2.67-.791 3.838-1.345a10.98 10.98 0 006.817-6.817C23.209 16.67 24 15.355 24 14c0-1.355-.791-2.67-1.345-3.838a10.98 10.98 0 00-6.817-6.817C14.67 2.791 13.355 2 12 2z"></path></svg></div><h2 class="text-lg font-semibold text-gray-800 mb-1">Gestionar Ciudades</h2><p class="text-gray-500 text-xs">Editar orígenes/destinos.</p></a>
                    <a href="#" class="admin-card flex flex-col p-6 bg-white rounded-xl shadow-lg border-b-4 border-teal-500 text-center items-center"><div class="p-4 rounded-full bg-teal-100 text-teal-600 mb-4 inline-block"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div><h2 class="text-lg font-semibold text-gray-800 mb-1">Ver Reservas</h2><p class="text-gray-500 text-xs">Consultar y gestionar.</p></a>
                </div>

            </main>

            {{-- Footer (Igual que antes) --}}
            <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
               &copy; {{ date('Y') }} Pasajes24/7 - Panel de Administración.
            </footer>
        </div>
    </div>

</body>
</html>