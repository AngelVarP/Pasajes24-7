<?php
// Usamos el array de sesión como fuente principal de datos
$reservaData = session('reserva_temporal');
// NOTA CLAVE: Si el controlador pasa $asientosResumen, lo usamos. Si no (ej. después de un error de validación), lo buscamos en la sesión.
$asientosResumen = $asientosResumen ?? $reservaData['asientos_detalles'] ?? []; 
$total = $total ?? $reservaData['total'] ?? 0;
$numAsientos = count($asientosResumen); 
// Los números de asiento para el título se extraen del nuevo array de resumen
$asientosSeleccionados = $asientosSeleccionados ?? collect($asientosResumen)->pluck('numero_asiento');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paso 3: Datos del Pasajero - {{ $viaje->ruta->origen->nombre }} a {{ $viaje->ruta->destino->nombre }}</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="icon" href="{{ asset('images/p-favicon.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* 1. Fondo de la página (DEL ADMIN) */
        .main-content-bg {
            background-color: #f0f4f8; 
            background-image: linear-gradient(180deg, #f0f4f8 0%, #d9e2ec 100%);
        }

        /* 2. ESTILO DE TÍTULO CON GRADIENTE */
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

<body class="main-content-bg text-gray-800 flex flex-col min-h-screen">

    {{-- Navbar con Barra de Progreso (Paso 3 Resaltado) --}}
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="transition duration-150">
                    <img src="{{ asset('images/logo-pasajes24-7-3.png') }}" alt="Logo Pasajes24/7" class="h-[40px] w-auto">
                </a>
                <div class="space-x-4">
                    <a href="#" class="text-gray-600 hover:text-blue-600 text-sm font-medium">Mis Pasajes</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 text-sm font-medium">Ayuda</a>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-200">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="flex items-center justify-between" aria-label="Progress">
                    
                    <div class="flex items-center text-blue-600 font-semibold">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">1. Búsqueda</span>
                    </div>

                    <div class="flex-1 h-0.5 bg-blue-600 mx-4" aria-hidden="true"></div>

                    <div class="flex items-center text-blue-600 font-semibold">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">2. Asientos</span>
                    </div>

                    <div class="flex-1 h-0.5 bg-amber-600 mx-4" aria-hidden="true"></div>
                    
                    <div class="flex items-center text-amber-600 font-bold">
                        <span class="relative flex h-5 w-5 items-center justify-center mr-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <span class="text-sm">3. Pago</span>
                    </div>
                </nav>
            </div>
        </div>
    </header>


    {{-- Contenido Principal --}}
    <main class="flex-grow py-8">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER Título principal --}}
            <div class="mb-6">
                <h1 class="header-title text-3xl font-extrabold drop-shadow-sm tracking-tight">
                    Ingresa tus Datos
                </h1>
                <p class="text-gray-600 mt-2">Completa la información de los **{{ $numAsientos }} pasajeros** y los datos de contacto para continuar con el pago.</p>
            </div>


            {{-- Contenedor Principal: Formulario y Resumen --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Columna 1 y 2: Formulario de Pasajeros --}}
                <div class="lg:col-span-2">
                    <form action="{{ route('reservas.procesar_pasajeros') }}" method="POST" class="space-y-6">
                        @csrf
                        {{-- Datos de la reserva (enviados de nuevo para validación) --}}
                        <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">
                        
                        {{-- IDs de asientos (pasamos los IDs de vuelta, ya validados) --}}
                        @foreach ($reservaData['asiento_ids'] ?? [] as $index => $asiento_id)
                            <input type="hidden" name="asiento_ids[]" value="{{ $asiento_id }}">
                        @endforeach
                        
                        
                        {{-- ----------------------------------------------------- --}}
                        {{-- Bloque de Datos de los Pasajeros (Dinámico por Asiento) --}}
                        {{-- (Fichas con borde azul) --}}
                        {{-- ----------------------------------------------------- --}}
                        @foreach ($asientosSeleccionados as $index => $numero_asiento)
                        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-600 transition duration-300 hover:shadow-xl">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Pasajero {{ $index + 1 }} (Asiento N° <span class="text-amber-600">{{ $numero_asiento }}</span>)
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                
                                {{-- Nombre --}}
                                <div>
                                    <label for="nombre_{{ $index }}" class="block text-sm font-bold text-gray-700 mb-1">Nombre</label>
                                    <input type="text" id="nombre_{{ $index }}" name="pasajeros[{{ $index }}][nombre]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="Nombre" required>
                                </div>
                                
                                {{-- Apellido --}}
                                <div>
                                    <label for="apellido_{{ $index }}" class="block text-sm font-bold text-gray-700 mb-1">Apellido</label>
                                    <input type="text" id="apellido_{{ $index }}" name="pasajeros[{{ $index }}][apellido]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="Apellido" required>
                                </div>
                                
                                {{-- DNI --}}
                                <div>
                                    <label for="dni_{{ $index }}" class="block text-sm font-bold text-gray-700 mb-1">DNI</label>
                                    <input type="text" id="dni_{{ $index }}" name="pasajeros[{{ $index }}][dni]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="DNI" required>
                                </div>

                                {{-- Edad --}}
                                <div>
                                    <label for="edad_{{ $index }}" class="block text-sm font-bold text-gray-700 mb-1">Edad</label>
                                    <input type="number" min="1" max="120" id="edad_{{ $index }}" name="pasajeros[{{ $index }}][edad]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="Edad" required>
                                </div>
                            </div>
                        </div>
                        @endforeach


                        {{-- ----------------------------------------------------- --}}
                        {{-- Bloque de Datos del Comprador (Contacto) --}}
                        {{-- (Ficha distinguida con borde ámbar) --}}
                        {{-- ----------------------------------------------------- --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-amber-500 transition duration-300 hover:shadow-xl">
                            <h2 class="text-xl font-bold text-amber-600 mb-4 pb-2 border-b border-gray-200">
                                Datos de Contacto (Comprador Principal)
                            </h2>
                            <p class="text-sm text-gray-600 mb-4">La confirmación de la reserva y los tickets se enviarán a este correo.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                {{-- Correo Electrónico --}}
                                <div>
                                    <label for="comprador_email" class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico</label>
                                    <input type="email" id="comprador_email" name="comprador[email]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="correo@ejemplo.com" required>
                                </div>

                                {{-- Teléfono (Opcional, pero útil) --}}
                                <div>
                                    <label for="comprador_telefono" class="block text-sm font-bold text-gray-700 mb-1">Teléfono (Opcional)</label>
                                    <input type="tel" id="comprador_telefono" name="comprador[telefono]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="9xxxxxxxxx">
                                </div>
                                
                                {{-- Nombre del Comprador --}}
                                <div>
                                    <label for="comprador_nombre" class="block text-sm font-bold text-gray-700 mb-1">Nombre Comprador</label>
                                    <input type="text" id="comprador_nombre" name="comprador[nombre]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="Nombre" required>
                                </div>
                                
                                {{-- DNI/RUC del Comprador --}}
                                <div>
                                    <label for="comprador_dni" class="block text-sm font-bold text-gray-700 mb-1">DNI/RUC Comprador</label>
                                    <input type="text" id="comprador_dni" name="comprador[dni]" 
                                           class="w-full py-2 px-3 border border-gray-200 bg-white rounded-md shadow-inner focus:border-amber-500 focus:ring-amber-500 transition duration-150" 
                                           placeholder="DNI/RUC" required>
                                </div>
                            </div>
                        </div>

                        
                        {{-- Botón Final del Formulario (Centrado y con sombra profunda) --}}
                        <div class="mt-8">
                            <button type="submit" 
                                    class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-extrabold text-lg rounded-lg transition duration-200 transform hover:scale-[1.01] shadow-xl hover:shadow-2xl">
                                Continuar a la Pasarela de Pago (S/ {{ number_format($total, 2) }})
                            </button>
                        </div>
                    </form>
                </div>


                {{-- Columna 3: Resumen de Reserva (STICKY Y MEJORADO) --}}
                <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-8">
                    
                    <div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
                        
                        {{-- Título con ícono --}}
                        <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                            <svg class="w-6 h-6 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-900">Detalles de tu Reserva</h3>
                        </div>

                        {{-- Detalles del Viaje --}}
                        <div class="space-y-3 text-sm pb-4 border-b border-gray-100">
                            <p class="flex justify-between">
                                <span class="text-gray-600">Ruta:</span>
                                <span class="font-semibold">{{ $viaje->ruta->origen->nombre }} → {{ $viaje->ruta->destino->nombre }}</span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600">Salida:</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('h:i A') }}</span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600">Empresa:</span>
                                <span class="font-semibold">{{ $viaje->empresa->nombre }}</span>
                            </p>
                        </div>

                        {{-- Asientos Seleccionados (CORREGIDO PARA USAR EL DESGLOSE DE PRECIOS) --}}
                        <div class="py-4 border-b border-gray-100">
                            <p class="text-md font-bold text-gray-700 mb-2">Asientos ({{ $numAsientos }}):</p>
                            <ul class="space-y-2"> {{-- Aumentamos el espaciado aquí --}}
                                @foreach ($asientosResumen as $asientoDetalle)
                                    <li class="flex justify-between text-sm items-center py-1 bg-gray-50 rounded px-2"> {{-- Fondo gris para resaltar cada ítem --}}
                                        {{-- LADO IZQUIERDO: Número de Asiento y Adicional --}}
                                        <span class="text-gray-700">
                                            Asiento N° <strong class="text-amber-600">{{ $asientoDetalle['numero_asiento'] }}</strong>
                                            @if($asientoDetalle['precio_adicional'] > 0)
                                                <span class="text-amber-500 text-xs font-medium">(+ S/ {{ number_format($asientoDetalle['precio_adicional'], 2) }} extra)</span>
                                            @endif
                                        </span>
                                        
                                        {{-- LADO DERECHO: Precio Unitario --}}
                                        <span class="font-bold text-gray-900">S/ {{ number_format($asientoDetalle['precio_unitario'], 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Precio Total --}}
                        <div class="pt-4 flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-900">TOTAL FINAL:</span>
                            <span class="text-4xl font-extrabold text-amber-600">S/ {{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- Footer (Estilo 'resultados.blade.php') --}}
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Pasajes24/7. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>