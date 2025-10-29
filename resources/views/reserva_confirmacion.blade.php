<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>¡Reserva Confirmada! - {{ $reserva->codigo_reserva }}</title>

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

        @keyframes pop-in {
            0% { opacity: 0; transform: scale(0.8) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-pop-in {
            animation: pop-in 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) both;
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) both;
        }

        {{-- ========================================================= --}}
        {{-- CAMBIO: Añadida regla para evitar cortes al imprimir --}}
        {{-- ========================================================= --}}
        @media print {
            .no-break-print {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

{{-- Fondo blanco al imprimir --}}
<body class="main-content-bg text-gray-800 flex flex-col min-h-screen print:bg-white">

    {{-- Ocultar el header al imprimir --}}
    <header class="bg-white shadow-md sticky top-0 z-50 print:hidden">
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
    </header>

    {{-- Quitar padding vertical al imprimir --}}
    <main class="flex-grow py-12 md:py-16 print:py-0">

        {{-- Quitar sombra al imprimir --}}
        <div class="container mx-auto max-w-4xl bg-white p-6 md:p-10 rounded-xl shadow-2xl animate-pop-in print:shadow-none">

            <div class="text-center pb-8 border-b border-gray-200">
                <svg class="w-20 h-20 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" class="text-green-500" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25z"/>
                    <path fill="currentColor" class="text-white" d="M15.47 9.47a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 1 1 1.06-1.06L10.5 13.69l3.97-3.97a.75.75 0 0 1 1.06 0z"/>
                </svg>
                <h1 class="text-4xl font-extrabold text-gray-900">¡Reserva Confirmada!</h1>
                <p class="text-lg text-gray-600 mt-2">Gracias por elegir Pasajes24-7. Tu viaje está listo.</p>
            </div>

            <div class="bg-amber-50 border-t-4 border-amber-400 text-amber-900 p-6 rounded-lg text-center my-8 shadow-inner animate-fade-in-up" style="animation-delay: 150ms;">
                <p class="text-lg font-medium">Tu Código de Reserva es:</p>
                <p class="text-5xl md:text-6xl font-extrabold tracking-widest my-3">{{ $reserva->codigo_reserva }}</p>
                <p class="text-sm">Lo necesitarás para abordar. También lo enviamos a <strong>{{ $reserva->email_comprador }}</strong>.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mt-10">
                <div class="lg:col-span-3 space-y-8 animate-fade-in-up" style="animation-delay: 300ms;">
                    
                    {{-- Pasajeros --}}
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Pasajeros</h3>
                        <div class="space-y-4">
                            @foreach($reserva->reservaAsientos as $pasajero)
                                {{-- CAMBIO: Añadida clase .no-break-print --}}
                                <div class="p-4 border border-gray-200 rounded-lg flex items-center justify-between bg-gray-50 no-break-print">
                                    <div>
                                        <p class="text-lg font-semibold">{{ $pasajero->pasajero_nombre }}</p>
                                        <p class="text-sm text-gray-600">DNI: {{ $pasajero->pasajero_dni }} | Edad: {{ $pasajero->pasajero_edad }}</p>
                                    </div>
                                    <div class="text-right pl-4">
                                        <span class="text-sm text-gray-600">Asiento</span>
                                        <p class="text-3xl font-bold text-amber-600">{{ $pasajero->asiento->numero_asiento }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Comprador (¡Estilo AZUL!) --}}
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Datos del Comprador</h3>
                        {{-- CAMBIO: Añadida clase .no-break-print --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-900 p-5 rounded-lg space-y-3 no-break-print">
                            <p><strong>Nombre:</strong> {{ $reserva->nombre_comprador }}</p>
                            <p><strong>DNI/RUC:</strong> {{ $reserva->dni_comprador }}</p>
                            <p><strong>Email:</strong> {{ $reserva->email_comprador }}</p>
                            <p><strong>Teléfono:</strong> {{ $reserva->telefono_comprador ?? 'No registrado' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha (Resumen del Viaje) --}}
                <div class="lg:col-span-2 animate-fade-in-up" style="animation-delay: 450ms;">
                    {{-- CAMBIO: Añadido print:static y .no-break-print --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 sticky top-24 print:static no-break-print">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">Resumen del Viaje</h3>
                        <div class="space-y-3 mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Ruta:</p>
                                <p class="text-lg font-semibold">{{ $reserva->viaje->ruta->origen->nombre }} &rarr; {{ $reserva->viaje->ruta->destino->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Salida:</p>
                                <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse($reserva->viaje->hora_salida)->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Empresa:</p>
                                <p class="text-lg font-semibold">{{ $reserva->viaje->empresa->nombre }}</p>
                            </div>
                        </div>
                        
                        <div class_alias="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-baseline">
                                <span class="text-lg font-medium text-gray-600">Total Pagado:</span>
                                <span class="text-4xl font-extrabold text-green-600">S/ {{ number_format($reserva->monto_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Ocultar los botones al imprimir --}}
            <div class="border-t border-gray-200 mt-10 pt-8 text-center animate-fade-in-up print:hidden" style="animation-delay: 600ms;">
                <a href="{{ route('home') }}" 
                   class="inline-block bg-blue-600 text-white font-bold py-3 px-10 rounded-lg transition duration-200 transform hover:scale-105 hover:bg-blue-700 shadow-lg">
                    Ir al Inicio
                </a>
                
                <button type="button" 
                   onclick="window.print();"
                   class="inline-block bg-white text-gray-700 font-bold py-3 px-10 rounded-lg transition duration-200 border border-gray-300 hover:bg-gray-100 ml-4">
                    Imprimir Ticket
                </button>
            </div>

        </div>
    </main>

    {{-- Ocultar el footer al imprimir --}}
    <footer class="bg-gray-800 text-white py-8 mt-16 print:hidden">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Pasajes24/7. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>