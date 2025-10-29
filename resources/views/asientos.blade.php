<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selección de Asientos - {{ $viaje->ruta->origen->nombre }} a {{ $viaje->ruta->destino->nombre }}</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Icono SVG de Asiento --}}
    @php
        $seatIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
            <path d="M17.25 12V10.5C17.25 8.57005 15.6799 7 13.75 7H10.25C8.32005 7 6.75 8.57005 6.75 10.5V12C5.7835 12 5 12.7835 5 13.75V17C5 18.1046 5.89543 19 7 19H17C18.1046 19 19 18.1046 19 17V13.75C19 12.7835 18.2165 12 17.25 12ZM8.25 10.5C8.25 9.40051 9.15051 8.5 10.25 8.5H13.75C14.8495 8.5 15.75 9.40051 15.75 10.5V12H8.25V10.5Z" />
        </svg>';
    @endphp

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* 1. Fondo de la página (¡EL DEL ADMIN!) */
        .main-content-bg {
            background-color: #f0f4f8; /* Color base */
            background-image: linear-gradient(180deg, #f0f4f8 0%, #d9e2ec 100%);
        }

        /* 2. Contenedor que simula el bus */
        .bus-container {
            border: 4px solid #9ca3af; /* gray-400 */
            background-color: #f3f4f6; /* gray-100 */
            border-radius: 2rem 2rem 1rem 1rem;
            padding: 1.5rem 1rem;
            position: relative;
        }

        /* 3. Cabina del conductor */
        .bus-driver-cab {
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 30%;
            height: 2rem;
            background-color: #9ca3af; /* gray-400 */
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
            border: 4px solid #9ca3af;
            border-bottom: none;
        }
        .bus-driver-cab::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 4px;
            height: 60%;
            background-color: #f3f4f6; /* gray-100 */
        }

        /* 4. Grilla del mapa de asientos (¡LA TUYA!) */
        .seat-map-grid { 
            display: grid;
            /* 2 asientos, pasillo ancho, 2 asientos */
            grid-template-columns: repeat(2, minmax(0, 1fr)) minmax(30px, auto) repeat(2, minmax(0, 1fr)); 
            gap: 0.75rem; /* 12px */
            margin-top: 1.5rem;
        } 

        /* 5. Asiento individual (con SVG) */
        .seat { 
            aspect-ratio: 1 / 1.1; 
            border-radius: 0.375rem; /* rounded-md */
            position: relative;
            border-width: 2px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .seat .seat-icon {
            position: absolute;
            inset: 0;
            padding: 0.25rem;
            opacity: 0.7;
        }
        .seat .seat-number {
            position: relative;
            z-index: 10;
            font-size: 0.75rem; /* text-xs */
            font-weight: 800; /* extrabold */
            color: #1f2937; /* gray-800 */
        }

        /* 6. Estados del Asiento (Colores "Bacan" + Animaciones) */
        .seat-available { 
            cursor: pointer; 
            background-color: #dbeafe; /* blue-100 */ 
            border-color: #93c5fd; /* blue-300 */
        }
        .seat-available .seat-icon { color: #60a5fa; } /* blue-400 */
        .seat-available:hover {
            background-color: #bfdbfe; /* blue-200 */
            border-color: #60a5fa; /* blue-400 */
            transform: scale(1.1);
            z-index: 10;
        }
        .seat-selected { 
            background-color: #f59e0b; /* amber-500 */ 
            border-color: #d97706; /* amber-600 */
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.4);
            z-index: 20;
        }
        .seat-selected .seat-icon { color: white; opacity: 1; }
        .seat-selected .seat-number { color: white; }
        .seat-occupied { 
            background-color: #6b7280; /* gray-500 */ 
            border-color: #4b5563; /* gray-600 */ 
            cursor: not-allowed; 
            opacity: 0.7; 
        }
        .seat-occupied .seat-icon { color: #d1d5db; opacity: 0.5; }
        .seat-occupied .seat-number { color: #d1d5db; }
        
        /* 7. ESTILO DE TÍTULO CON GRADIENTE */
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
{{-- Aplicamos el fondo del admin al body --}}
<body class="main-content-bg text-gray-800 flex flex-col min-h-screen">

    {{-- Navbar (Blanco y limpio con barra de progreso) --}}
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
                <nav class="flex items-center justify-between" aria-label="Progress">
                    
                    <div class="flex items-center text-blue-600 font-semibold">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">1. Búsqueda</span>
                    </div>

                    {{-- Línea de conexión --}}
                    <div class="flex-1 h-0.5 bg-blue-600 mx-4" aria-hidden="true"></div>

                    <div class="flex items-center text-amber-600 font-bold">
                        <span class="relative flex h-5 w-5 items-center justify-center mr-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <span class="text-sm">2. Asientos</span>
                    </div>

                    {{-- Línea de conexión --}}
                    <div class="flex-1 h-0.5 bg-gray-300 mx-4" aria-hidden="true"></div>
                    
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


    {{-- Contenido Principal --}}
    <main class="flex-grow py-8" 
          x-data="asientoSelector({{ $viaje->asientos->toJson() }}, {{ $viaje->precio }})">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER "BACAN" - Título principal con estilo de gradiente --}}
            {{-- Se eliminó el botón 'Volver' y se quitó 'justify-between' para que el título no ocupe todo el espacio --}}
            <div class="flex items-center mb-6"> 
                <h1 class="header-title text-3xl font-extrabold drop-shadow-sm tracking-tight">
                    Selecciona tu Asiento
                </h1>
                {{-- EL BOTÓN 'VOLVER A RESULTADOS' FUE ELIMINADO SEGÚN SOLICITUD --}}
            </div>


            {{-- Detalles del Viaje (Tarjeta estilo admin, con borde ámbar) --}}
            <div class="flex flex-col md:flex-row justify-between items-start bg-white p-6 rounded-xl shadow-lg mb-8 border-l-4 border-amber-500">
                <div>
                    <p class="text-lg font-semibold text-gray-700">Ruta: {{ $viaje->ruta->origen->nombre }} &rarr; {{ $viaje->ruta->destino->nombre }}</p>
                    <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($viaje->hora_salida)->format('h:i A') }} - {{ $viaje->empresa->nombre }}</p>
                    <p class="text-sm text-gray-500">{{ $viaje->tipo_servicio ?? 'Servicio Estándar' }} | Fecha: {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/M/Y') }}</p>
                </div>
                <img src="{{ asset('images/Empresas/' . Str::slug($viaje->empresa->nombre) . '.png') }}" onerror="this.onerror=null;this.src='https://placehold.co/100x40/CCCCCC/FFFFFF?text=LOGO';" alt="Logo {{ $viaje->empresa->nombre }}" class="h-10 w-auto mt-4 md:mt-0">
            </div>

            {{-- Contenedor Principal: Mapa y Resumen --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Columna 1 y 2: Mapa del Bus (Tarjeta blanca) --}}
                <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200"
                     x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
                    
                    <div x-show="shown" x-transition:enter="transition ease-out duration-500" 
                         x-transition:enter-start="opacity-0 translate-y-4" 
                         x-transition:enter-end="opacity-100 translate-y-0">
                    
                        @php
                            $pisos = $viaje->asientos->groupBy('piso');
                        @endphp

                        @foreach ($pisos as $piso => $asientosDelPiso)
                            
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Piso {{ $piso }}</h2>
                            
                            {{-- Leyenda --}}
                            <div class="flex flex-wrap space-x-4 justify-center mb-6 text-xs text-gray-600">
                                <span class="flex items-center mb-2"><div class="w-4 h-4 rounded-sm mr-1 border border-blue-300 bg-blue-100"></div> Disponible</span>
                                <span class="flex items-center mb-2"><div class="w-4 h-4 rounded-sm mr-1 bg-amber-500 border border-amber-600"></div> Seleccionado</span>
                                <span class="flex items-center mb-2"><div class="w-4 h-4 rounded-sm mr-1 bg-gray-600 border border-gray-700"></div> Ocupado</span>
                            </div>

                            {{-- Contenedor del Mapa (Simulación de Bus) --}}
                            <div class="bus-container max-w-sm mx-auto"> 

                                @if ($piso == 1)
                                    <div class="bus-driver-cab"></div>
                                @endif

                                {{-- Volvemos a TU lógica de grid (la de 5 columnas) --}}
                                <div class="seat-map-grid">
                                    
                                    {{-- Iteramos solo los asientos de ESTE piso --}}
                                    <template x-for="seat in asientos.filter(s => s.piso == {{ $piso }})" :key="seat.id">
                                        
                                        {{-- Usamos TU LÓGICA de filtro de 'pasillo' --}}
                                        <template x-if="seat.numero_asiento !== 'pasillo'">
                                            <div class="seat"
                                                 :class="{
                                                     'seat-available': seat.estado === 'disponible' && !selectedSeats.includes(seat.id),
                                                     'seat-selected': selectedSeats.includes(seat.id),
                                                     'seat-occupied': seat.estado === 'ocupado'
                                                 }"
                                                 @click="toggleSeat(seat)"
                                            >
                                                {{-- PERO usamos MI DISEÑO de asiento --}}
                                                <div class="seat-icon">{!! $seatIcon !!}</div>
                                                <span class="seat-number" x-text="seat.numero_asiento"></span>
                                            </div>
                                        </template>

                                    </template>

                                </div> {{-- fin .seat-map-grid --}}
                            </div> {{-- fin .bus-container --}}
                            
                            @if (!$loop->last)
                                <div class="border-t border-gray-200 my-8"></div>
                            @endif
                        @endforeach

                    </div>
                </div>

                {{-- Columna 3: Resumen de Reserva (Tarjeta blanca, Sticky) --}}
                <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-24">
                    
                    <form action="{{ route('reservas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">

                        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Resumen de tu Selección</h2>

                            <p class="text-sm text-gray-500 mb-3">Asientos seleccionados: <strong x-text="selectedSeats.length"></strong></p>

                            <div class="mb-4 min-h-[50px] max-h-48 overflow-y-auto space-y-2 pr-1">
                                <template x-if="selectedSeats.length > 0">
                                    <div class="space-y-2">
                                        <template x-for="seat in selectedSeatObjects" :key="seat.id">
                                            <div class="flex justify-between items-center text-sm font-medium bg-amber-100 text-amber-800 px-3 py-2 rounded-md border border-amber-200">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.25 12V10.5C17.25 8.57005 15.6799 7 13.75 7H10.25C8.32005 7 6.75 8.57005 6.75 10.5V12C5.7835 12 5 12.7835 5 13.75V17C5 18.1046 5.89543 19 7 19H17C18.1046 19 19 18.1046 19 17V13.75C19 12.7835 18.2165 12 17.25 12ZM8.25 10.5C8.25 9.40051 9.15051 8.5 10.25 8.5H13.75C14.8495 8.5 15.75 9.40051 15.75 10.5V12H8.25V10.5Z" /></svg>
                                                    Asiento <strong x-text="seat.numero_asiento" class="mx-1"></strong>
                                                    <span class="text-xs text-amber-700">(Piso <span x-text="seat.piso"></span>)</span>
                                                </div>
                                                <span class="font-bold">S/ <span x-text="formatPrice(seat.precio_base + seat.precio_adicional)"></span></span>
                                                <input type="hidden" name="asientos[]" :value="seat.id">
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <p x-show="selectedSeats.length === 0" class="text-sm text-gray-400 italic pt-2">Selecciona uno o más asientos en el mapa.</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                 <div class="flex justify-between items-center text-2xl font-bold mb-5">
                                    <span>Total:</span>
                                    <span class="text-amber-600" x-text="'S/ ' + formatPrice(totalPrice)"></span>
                                </div>
                                <button type="submit" 
                                        class="w-full py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-lg transition duration-200 transform hover:scale-[1.03] shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100 disabled:shadow-lg"
                                        :disabled="selectedSeats.length === 0">
                                    Continuar a Pago (<span x-text="selectedSeats.length"></span>)
                                </button>
                            </div>
                        </div>
                    </form>
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

    {{-- Script de Alpine.js (Tu lógica original) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('asientoSelector', (initialSeats, basePrice) => ({
                asientos: initialSeats.map(seat => ({
                    ...seat,
                    precio_base: parseFloat(basePrice) || 0,
                    precio_adicional: parseFloat(seat.precio_adicional) || 0,
                })),
                selectedSeats: [], 
                maxSeats: 6, 

                toggleSeat(seat) {
                    if (seat.estado !== 'disponible') return; 
                    const index = this.selectedSeats.indexOf(seat.id);
                    if (index > -1) {
                        this.selectedSeats.splice(index, 1);
                    } else {
                        if (this.selectedSeats.length >= this.maxSeats) {
                            alert(`Solo puedes seleccionar un máximo de ${this.maxSeats} asientos.`);
                            return;
                        }
                        this.selectedSeats.push(seat.id);
                    }
                },

                get selectedSeatObjects() {
                    return this.asientos
                        .filter(seat => this.selectedSeats.includes(seat.id))
                        .sort((a, b) => a.numero_asiento - b.numero_asiento);
                },

                get totalPrice() {
                    return this.selectedSeatObjects.reduce((sum, seat) => {
                        return sum + seat.precio_base + seat.precio_adicional;
                    }, 0);
                },

                formatPrice(price) {
                    const numPrice = parseFloat(price);
                    return isNaN(numPrice) ? '0.00' : numPrice.toFixed(2);
                }
            }));
        });
    </script>

</body>
</html>