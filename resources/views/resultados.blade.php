<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados de Búsqueda - Pasajes24/7</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    {{-- Navbar (similar al de welcome.blade.php) --}}
    <header class="bg-gray-900 shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="transition duration-150 drop-shadow-md">
                    <img src="{{ asset('images/logo-pasajes24-7-2.png') }}" alt="Logo Pasajes24/7" class="h-[60px] w-auto">
                </a>
                <a href="#" class="text-sm font-medium bg-white text-gray-800 px-4 py-2 rounded-full shadow-sm hover:bg-gray-200 transition duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span>Iniciar Sesión</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Encabezado de Resultados --}}
        <div class="mb-8 p-6 bg-white rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-gray-800">Resultados de Viaje</h1>
            <p class="text-lg text-gray-600">
                Mostrando viajes de <span class="font-semibold text-amber-600">{{ $origen->nombre }}</span> 
                a <span class="font-semibold text-amber-600">{{ $destino->nombre }}</span> 
                para el <span class="font-semibold text-amber-600">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</span>
            </p>
            {{-- Aquí podrías poner filtros (precio, hora, empresa) --}}
        </div>

        {{-- Lista de Resultados --}}
        <div class="space-y-6">
            
            @forelse ($viajes as $viaje)
                {{-- Tarjeta de Viaje Individual --}}
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <div class="flex flex-col md:flex-row items-center">
                        
                        {{-- 1. Información de la Empresa y Servicio --}}
                        <div class="w-full md:w-1/4 p-5 text-center md:text-left border-b md:border-b-0 md:border-r border-gray-100">
                            <img src="{{ asset($viaje->empresa->logo_url) ?? 'https://via.placeholder.com/100/CCCCCC/FFFFFF?text=Logo' }}" alt="Logo de {{ $viaje->empresa->nombre }}" class="h-12 mx-auto md:mx-0 mb-2">
                            <h3 class="font-bold text-lg text-gray-800">{{ $viaje->empresa->nombre }}</h3>
                            <span class="inline-block bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded-full mt-2">{{ $viaje->tipo_servicio }}</span>
                        </div>

                        {{-- 2. Horarios y Duración --}}
                        <div class="w-full md:w-1/2 p-5 flex justify-around items-center border-b md:border-b-0 md:border-r border-gray-100">
                            <div class="text-center">
                                <span class="text-sm text-gray-500">Sale</span>
                                <p class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($viaje->hora_salida)->format('h:i A') }}</p>
                                <span class="text-sm text-gray-500">{{ $viaje->ruta->origen->nombre }}</span>
                            </div>
                            
                            <div class="text-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                <span class="text-xs block mt-1">{{ $viaje->ruta->duracion_estimada_minutos ? floor($viaje->ruta->duracion_estimada_minutos / 60) . 'h ' . ($viaje->ruta->duracion_estimada_minutos % 60) . 'm' : '' }}</span>
                            </div>
                            
                            <div class="text-center">
                                <span class="text-sm text-gray-500">Llega (aprox.)</span>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($viaje->fecha_salida . ' ' . $viaje->hora_salida)->addMinutes($viaje->ruta->duracion_estimada_minutos ?? 0)->format('h:i A') }}
                                </p>
                                <span class="text-sm text-gray-500">{{ $viaje->ruta->destino->nombre }}</span>
                            </div>
                        </div>

                        {{-- 3. Precio y Selección --}}
                        <div class="w-full md:w-1/4 p-5 text-center">
                            <p class="text-3xl font-extrabold text-gray-800">S/ {{ number_format($viaje->precio, 2) }}</p>
                            <span class="text-sm text-gray-500 mb-3 block">{{ $viaje->asientos_disponibles }} asientos disponibles</span>
                            {{-- Este enlace llevará a la selección de asientos --}}
                            <a href="{{-- route('viajes.asientos', $viaje->id) --}}" class="w-full inline-block bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:from-amber-600 hover:to-orange-600 transition duration-300 transform hover:scale-105">
                                Ver Asientos
                            </a>
                        </div>
                        
                    </div>
                </div>
            
            @empty
                {{-- Mensaje si no se encuentran viajes --}}
                <div class="bg-white rounded-lg shadow-lg p-10 text-center">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">¡Lo sentimos!</h3>
                    <p class="text-gray-500">No se encontraron viajes disponibles para la ruta y fecha seleccionadas.</p>
                    <a href="/" class="mt-6 inline-block bg-gray-800 text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-700 transition duration-300">
                        Volver a Buscar
                    </a>
                </div>
            @endforelse

        </div>
    </main>

</body>
</html>