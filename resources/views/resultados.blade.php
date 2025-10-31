<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados: {{ $origen->nombre }} a {{ $destino->nombre }}</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .main-content-bg {
            background-color: #f0f4f8; 
            background-image: linear-gradient(180deg, #f0f4f8 0%, #d9e2ec 100%);
        }
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) both;
        }
    </style>
</head>

<body class="main-content-bg text-gray-800 flex flex-col min-h-screen">

    {{-- ============================================= --}}
    {{-- HEADER (Tomado de asientos.blade.php) --}}
    {{-- ============================================= --}}
    <header class="bg-white shadow-md sticky top-0 z-50">
        {{-- Parte Superior del Navbar (Logo y Links) --}}
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="transition duration-150">
                    <img src="{{ asset('images/logo-pasajes24-7-2.png') }}" alt="Logo Pasajes24/7" class="h-[90px] w-auto">
                </a>
                <div class="space-x-4">
                    <a href="#" class="text-gray-600 hover:text-blue-600 text-sm font-medium">Mis Pasajes</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 text-sm font-medium">Ayuda</a>
                </div>
            </div>
        </div>
        
        {{-- Parte Inferior del Navbar (Barra de Progreso) --}}
        <div class="border-t border-gray-200">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
                {{-- ========================================================= --}}
                {{-- CORRECCIÓN: Quitado 'max-w-xl mx-auto' --}}
                {{-- ========================================================= --}}
                <nav class="flex items-center justify-between" aria-label="Progress">
                    
                    {{-- Paso 1: Búsqueda (ACTIVO) --}}
                    <div class="flex items-center text-amber-600 font-bold">
                        <span class="relative flex h-5 w-5 items-center justify-center mr-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <span class="text-sm">1. Búsqueda</span>
                    </div>

                    {{-- Línea de conexión (Inactiva) --}}
                    <div class="flex-1 h-0.5 bg-gray-300 mx-4" aria-hidden="true"></div>

                    {{-- Paso 2: Asientos (INACTIVO) --}}
                    <div class="flex items-center text-gray-400 font-medium">
                        <div class="w-5 h-5 mr-2 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-gray-300 border-2 border-gray-400"></div>
                        </div>
                        <span class="text-sm">2. Asientos</span>
                    </div>

                    {{-- Línea de conexión (Inactiva) --}}
                    <div class="flex-1 h-0.5 bg-gray-300 mx-4" aria-hidden="true"></div>
                    
                    {{-- Paso 3: Pago (INACTIVO) --}}
                    <div class="flex items-center text-gray-400 font-medium">
                        <div class="w-5 h-5 mr-2 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-gray-300 border-2 border-gray-400"></div>
                        </div>
                        <span class="text-sm">3. Pago</span>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    {{-- ============================================= --}}
    {{-- BARRA DE RESUMEN DE BÚSQUEDA --}}
    {{-- ============================================= --}}
    <div class="bg-white shadow-inner">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $origen->nombre }}
                        <span class="text-blue-600 mx-2">&rarr;</span>
                        {{ $destino->nombre }}
                    </h2>
                    <span class="text-xl font-semibold text-gray-600">|</span>
                    <span class="text-xl font-semibold text-gray-600">
                        {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd D [de] MMMM') }}
                    </span>
                </div>
                <a href="{{ route('home') }}" class="mt-2 md:mt-0 inline-block bg-amber-500 text-white font-bold py-2 px-5 rounded-lg transition duration-200 hover:bg-amber-600">
                    Modificar Búsqueda
                </a>
            </div>
        </div>
    </div>


    {{-- ============================================= --}}
    {{-- LAYOUT (Filtros + Resultados) --}}
    {{-- ============================================= --}}
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- COLUMNA DE FILTROS (IZQUIERDA) --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-3">Filtrar por</h3>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Empresa</h4>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-blue-600">
                                <span>Cruz del sur</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-blue-600">
                                <span>Civa</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-blue-600">
                                <span>Oltursa</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Hora de Salida</h4>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-blue-600">
                                <span>Mañana (5am - 12pm)</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-blue-600">
                                <span>Tarde (12pm - 7pm)</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="rounded text-blue-600">
                                <span>Noche (7pm - 5am)</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Ordenar por</h4>
                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option>Precio: más bajo</option>
                            <option>Precio: más alto</option>
                            <option>Hora: más temprano</option>
                            <option>Hora: más tarde</option>
                        </select>
                    </div>
                </div>
            </aside>

            {{-- LISTA DE RESULTADOS (DERECHA) --}}
            <section class="lg:col-span-3">
                <div class="space-y-6">

                    @forelse($viajes as $viaje)
                        {{-- NUEVA TARJETA DE RESULTADO DE VIAJE --}}
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in-up" 
                             style="animation-delay: {{ $loop->index * 100 }}ms;">
                            <div class="flex flex-col md:flex-row">
                                
                                <div class="flex-grow p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-4">
                                            <h3 class="text-2xl font-bold text-gray-900">{{ $viaje->empresa->nombre }}</h3>
                                        </div>
                                        <span class="text-sm font-medium text-blue-600 bg-blue-100 px-3 py-1 rounded-full">{{ $viaje->tipo_servicio }}</span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 text-center items-center border-t border-b border-gray-100 py-4">
                                        <div>
                                            <p class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($viaje->hora_salida)->format('h:i A') }}</p>
                                            <p class="text-sm text-gray-600">{{ $viaje->ruta->origen->nombre }}</p>
                                        </div>
                                        <div class="text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                            <p class="text-sm">{{ $viaje->ruta->duracion_estimada_minutos ? floor($viaje->ruta->duracion_estimada_minutos / 60) . 'h ' . ($viaje->ruta->duracion_estimada_minutos % 60) . 'm' : '' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($viaje->fecha_salida . ' ' . $viaje->hora_salida)->addMinutes($viaje->ruta->duracion_estimada_minutos ?? 0)->format('h:i A') }}
                                            </p>
                                            <p class="text-sm text-gray-600">{{ $viaje->ruta->destino->nombre }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 md:w-1/4 p-6 flex flex-row md:flex-col justify-between md:justify-center items-center md:text-center space-y-0 md:space-y-3">
                                    <div>
                                        <p class="text-3xl font-extrabold text-green-600">S/ {{ number_format($viaje->precio, 2) }}</p>
                                        <p class="text-sm text-gray-600">{{ $viaje->asientos_disponibles }} asientos disponibles</p>
                                    </div>
                                    <a href="{{ route('viajes.asientos', $viaje) }}"
                                       class="inline-block bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 hover:bg-blue-700 shadow-lg">
                                        Ver Asientos
                                    </a>
                                </div>
                            </div>
                        </div>
                    
                    @empty
                        {{-- NUEVA TARJETA DE "NO HAY RESULTADOS" --}}
                        <div class="bg-white rounded-xl shadow-lg p-12 text-center col-span-full">
                            <svg class="w-20 h-20 text-amber-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">¡Ups! No hay viajes</h2>
                            <p class="text-lg text-gray-600">
                                No encontramos salidas para la ruta y fecha seleccionada.
                                <br>
                                Intenta modificando tu búsqueda.
                            </p>
                        </div>
                    @endforelse

                </div>
            </section>
        </div>

    </main>

    {{-- ============================================= --}}
    {{-- FOOTER (Consistente) --}}
    {{-- ============================================= --}}
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Pasajes24/7. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>