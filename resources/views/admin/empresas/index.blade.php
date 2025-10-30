<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gestionar Empresas - Admin</title>

{{-- Estilos (Mismos de la plantilla base) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
body { font-family: 'Inter', sans-serif; }
.admin-sidebar { background-color: #111827; }
.admin-sidebar a { transition: all 0.2s ease-in-out; }
.admin-sidebar a:hover, .admin-sidebar a.active { background-color: #374151; color: #f59e0b; }
.admin-sidebar a.active { border-left: 4px solid #f59e0b; padding-left: calc(1rem - 4px); font-weight: 600; }
.main-content-bg { background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 100%); }
.header-title { background: linear-gradient(to right, #f59e0b, #f97316); -webkit-background-clip: text; background-clip: text; color: transparent; font-weight: 800; letter-spacing: -0.025em; }
input:focus, select:focus, button:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4); border-color: #f59e0b !important; }
</style>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">
{{-- Sidebar (Navegación) --}}
<aside class="admin-sidebar w-64 text-gray-300 hidden md:flex md:flex-col fixed inset-y-0 z-50 shadow-lg">
{{-- Logo y Menú (Usando la estructura de tu Index base) --}}
<div class="flex items-center justify-center h-20 border-b border-gray-700">
<a href="/"><img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-12 w-auto filter brightness-0 invert"></a>
</div>
<nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
<a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg><span>Dashboard</span></a>
<div class="pt-4 pb-2 px-4"><span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión Principal</span></div>
<a href="{{ route('admin.viajes.create') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500 {{ request()->routeIs('admin.viajes.create') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Crear Viaje</span></a>
<a href="{{ route('admin.viajes.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500 {{ request()->routeIs('admin.viajes.index') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg><span>Gestionar Viajes</span></a>
<a href="{{ route('admin.rutas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500 {{ request()->routeIs('admin.rutas.index') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><span>Gestionar Rutas</span></a>
<a href="{{ route('admin.empresas.index') }}" class="active flex items-center px-4 py-2.5 rounded-lg text-sm font-medium">
<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg><span>Gestionar Empresas</span></a>
<a href="{{ route('admin.ciudades.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500 {{ request()->routeIs('admin.ciudades.index') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.09M19.945 11H19a2 2 0 00-2 2v1a2 2 0 01-2 2 2 2 0 00-2 2v2.945M16 3.935V5.5A2.5 2.5 0 0113.5 8h-.09M12 21v-2.055A2.5 2.5 0 0114.5 16h-.09M12 3c-1.355 0-2.67.291-3.838.813a10.98 10.98 0 00-6.817 6.817C.791 11.33 0 12.645 0 14c0 1.355.791 2.67 1.345 3.838a10.98 10.98 0 006.817 6.817C9.33 24.209 10.645 25 12 25c1.355 0 2.67-.791 3.838-1.345a10.98 10.98 0 006.817-6.817C23.209 16.67 24 15.355 24 14c0-1.355-.791-2.67-1.345-3.838a10.98 10.98 0 00-6.817-6.817C14.67 2.791 13.355 2 12 2z"></path></svg><span>Gestionar Ciudades</span></a>
<a href="{{ route('admin.reservas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-amber-500 {{ request()->routeIs('admin.reservas.index') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><span>Ver Reservas</span></a>
</nav>
<div class="mt-auto p-4 border-t border-gray-700">
<form method="POST" action="{{ route('admin.logout') }}"><button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-900 hover:text-red-300 transition duration-150"><svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>Cerrar Sesión</button></form>
</div>
</aside>

<div class="flex-1 flex flex-col md:ml-64">
<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 border-b border-gray-200">
<div class="flex items-center">
<button class="md:hidden mr-4 text-gray-500 hover:text-gray-700"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
<h1 class="header-title text-2xl font-extrabold tracking-tight">Gestionar Empresas</h1>
</div>
<div class="flex items-center space-x-4">
<span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
</div>
</header>

<main class="flex-grow p-6 lg:p-10 main-content-bg">

{{-- Encabezado y Botón Crear --}}
<div class="flex justify-between items-center mb-8 border-b pb-4 border-gray-300">
<h2 class="text-2xl font-bold text-gray-800 flex items-center">
<svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
Listado de Empresas Registradas
</h2>

<a href="{{ route('admin.empresas.create') }}"
class="bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg hover:from-amber-600 hover:to-orange-600 transition duration-200 transform hover:-translate-y-0.5 flex items-center text-sm">
<svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
Añadir Nueva Empresa
</a>
</div>

{{-- Mensajes de Éxito/Error --}}
@if (session('success'))
<div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
<span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif
@if (session('error'))
<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
<span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

{{-- Contenedor de la Tabla --}}
<div class="bg-white rounded-lg shadow-xl overflow-x-auto">
<table class="min-w-full divide-y divide-gray-200">
<thead>
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">ID</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Nombre</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">RUC</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Contacto</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Acciones</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
@forelse ($empresas as $empresa)
<tr>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $empresa->id }}</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
    <div class="font-medium">{{ $empresa->nombre }}</div>
    <div class="text-xs text-gray-500">{{ $empresa->logo_url ? 'Logo Presente' : 'Sin logo' }}</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $empresa->ruc }}</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
    <div class="text-xs text-gray-500">{{ $empresa->email_contacto }}</div>
    <div class="text-xs text-gray-500">{{ $empresa->telefono_contacto }}</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
<a href="{{ route('admin.empresas.edit', $empresa) }}"
class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
Editar
</a>

<form action="{{ route('admin.empresas.destroy', $empresa) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar la empresa: {{ addslashes($empresa->nombre ?? 'N/A') }}?\n\n¡Si tiene viajes asociados, NO podrás eliminarla!');">
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
<td colspan="5" class="px-6 py-10 text-center text-gray-500">No hay empresas de transporte registradas. ¡Crea una nueva!</td>
</tr>
@endforelse
</tbody>
</table>

{{-- Links de Paginación --}}
<div class="p-4 border-t border-gray-200 bg-white">
{{ $empresas->links() }}
</div>
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