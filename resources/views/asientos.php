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
    <link rel="icon" href="{{ asset('images/p-favicon.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; } /* slate-50 */
        /* Definimos 4 columnas de asientos y 1 columna para el pasillo (más ancha) */
        .seat-map-grid { 
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr)) minmax(30px, auto) repeat(2, minmax(0, 1fr)); 
            gap: 0.75rem; /* Ajusta el espacio entre asientos */
        } 
        .aisle { grid-column: 3 / 4; } /* El pasillo ocupa la 3ra columna */

        /* Estilos básicos para los asientos (puedes personalizarlos más) */
        .seat { 
            height: 40px; 
            border-radius: 0.375rem; /* rounded-md */
            font-size: 0.75rem; /* text-xs */
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }
        .seat-available { 
            cursor: pointer; 
            background-color: #e5e7eb; /* gray-200 */ 
            color: #374151; /* gray-700 */
            border-color: #9ca3af; /* gray-400 */
        }
        .seat-available:hover {
             background-color: #d1d5db; /* gray-300 */
             border-color: #6b7280; /* gray-500 */
        }
        .seat-selected { 
            background-color: #f59e0b; /* amber-500 */ 
            color: white; 
            border-color: #d97706; /* amber-600 */
            transform: scale(1.05); 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md */
        } 
        .seat-occupied { 
            background-color: #6b7280; /* gray-500 */ 
            color: #d1d5db; /* gray-300 */ 
            cursor: not-allowed; 
            opacity: 0.7; 
            border-color: #4b5563; /* gray-600 */
        } 
        .seat-driver { 
            background-color: #1f2937; /* gray-800 */ 
            color: white; 
            border-radius: 0.5rem; /* rounded-lg */
            font-size: 0.75rem; /* text-xs */
            height: auto; /* Altura automática */
            padding: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    {{-- Navbar (Sólido para el flujo de reserva) --}}
    <header class="bg-gray-900 shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="transition duration-150 drop-shadow-md">
                    <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-[60px] w-auto">
                </a>
                <div class="text-white text-sm font-medium">
                    {{-- Enlace para volver a los resultados --}}
                    <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-white">&larr; Volver a Resultados</a> 
                </div>
            </div>
        </div>
    </header>

    {{-- Contenido Principal --}}
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12" 
          x-data="asientoSelector({{ $viaje->asientos->toJson() }}, {{ $viaje->precio }})">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-4">
            Selecciona tu Asiento
        </h1>

        {{-- Detalles del Viaje --}}
        <div class="flex flex-col md:flex-row justify-between items-start bg-white p-6 rounded-xl shadow-lg mb-8 border-l-4 border-amber-500">
            {{-- ... (código para mostrar detalles del viaje, igual que antes) ... --}}
            <div>
                <p class="text-lg font-semibold text-gray-700">Ruta: {{ $viaje->ruta->origen->nombre }} &rarr; {{ $viaje->ruta->destino->nombre }}</p>
                <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($viaje->hora_salida)->format('h:i A') }} - {{ $viaje->empresa->nombre }}</p>
                <p class="text-sm text-gray-500">{{ $viaje->tipo_servicio }} | Fecha: {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/M/Y') }}</p>
            </div>
            <img src="{{ asset($viaje->empresa->logo_url) }}" onerror="this.onerror=null;this.src='https://placehold.co/100x40/CCCCCC/FFFFFF?text=LOGO';" alt="Logo" class="h-10 w-auto mt-4 md:mt-0">
        </div>

        {{-- Contenedor Principal: Mapa y Resumen --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Columna 1 y 2: Mapa del Bus --}}
            <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-xl font-semibold mb-6">Mapa del Bus (Piso 1)</h2>

                {{-- Leyenda --}}
                <div class="flex flex-wrap space-x-4 justify-center mb-6 text-xs text-gray-600">
                    <span class="flex items-center mb-2"><div class="w-4 h-4 rounded-sm mr-1 border border-gray-400 bg-gray-200"></div> Disponible</span>
                    <span class="flex items-center mb-2"><div class="w-4 h-4 rounded-sm mr-1 bg-amber-500 border border-amber-600"></div> Seleccionado</span>
                    <span class="flex items-center mb-2"><div class="w-4 h-4 rounded-sm mr-1 bg-gray-500 border border-gray-600"></div> Ocupado</span>
                </div>

                {{-- Contenedor del Mapa --}}
                {{-- Usamos max-w-xs o sm para controlar el ancho --}}
                <div class="max-w-xs sm:max-w-sm mx-auto border-4 border-gray-300 rounded-lg p-4 bg-gray-50"> 

                    {{-- Área del Conductor --}}
                    <div class="mb-4 h-10 w-full relative text-center">
                        <div class="inline-block seat-driver px-4 py-1">
                           Conductor
                        </div>
                    </div>

                    {{-- Grilla de Asientos --}}
                    <div class="seat-map-grid">

                        {{-- Iterar sobre los asientos pasados desde el controlador --}}
                        <template x-for="seat in asientos" :key="seat.id">
                            {{-- Si el asiento es el pasillo (columna 3), no renderizar div --}}
                            <template x-if="seat.numero_asiento !== 'pasillo'">
                                <div class="seat transition transform"
                                     :class="{
                                         'seat-available': seat.estado === 'disponible' && !selectedSeats.includes(seat.id),
                                         'seat-selected': selectedSeats.includes(seat.id),
                                         'seat-occupied': seat.estado === 'ocupado'
                                     }"
                                     @click="toggleSeat(seat)"
                                >
                                    <span x-text="seat.numero_asiento"></span>
                                </div>
                            </template>
                        </template>

                    </div>
                </div>
            </div>

            {{-- Columna 3: Resumen de Reserva --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Resumen de tu Selección</h2>

                    <p class="text-sm text-gray-500 mb-3">Asientos seleccionados: <strong x-text="selectedSeats.length"></strong></p>

                    {{-- Lista de asientos seleccionados --}}
                    <div class="mb-4 min-h-[50px]"> {{-- Altura mínima para evitar saltos --}}
                        <template x-if="selectedSeats.length > 0">
                            <div class="space-y-2">
                                <template x-for="seat in selectedSeatObjects" :key="seat.id">
                                    <div class="flex justify-between items-center text-sm font-medium bg-amber-50 px-3 py-1.5 rounded">
                                        <span>
                                            Asiento <strong x-text="seat.numero_asiento"></strong>
                                        </span>
                                        <span class="text-amber-700">S/ <span x-text="formatPrice(seat.precio_base + seat.precio_adicional)"></span></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <p x-show="selectedSeats.length === 0" class="text-sm text-gray-400 italic">Selecciona uno o más asientos en el mapa.</p>
                    </div>

                    {{-- Precio Total --}}
                    <div class="border-t border-gray-200 pt-4">
                         <div class="flex justify-between items-center text-2xl font-bold mb-5">
                            <span>Total:</span>
                            <span class="text-amber-600" x-text="'S/ ' + formatPrice(totalPrice)"></span>
                        </div>

                        {{-- Botón Continuar (Deshabilitado si no hay asientos) --}}
                        <button class="w-full py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-lg transition duration-200 transform hover:scale-[1.01] disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="selectedSeats.length === 0"
                                @click.prevent="continuarReserva()"> {{-- Llamar a la función continuarReserva --}}
                            Continuar (<span x-text="selectedSeats.length"></span>)
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    {{-- Footer (Simple) --}}
    <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
       &copy; {{ date('Y') }} Pasajes24/7.
    </footer>

    {{-- Script de Alpine.js para la lógica de asientos --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('asientoSelector', (initialSeats, basePrice) => ({
                // Convertimos la data JSON de asientos a un array usable
                asientos: initialSeats.map(seat => ({
                    ...seat,
                    precio_base: parseFloat(basePrice) || 0, // Asegura que el precio base sea número
                    precio_adicional: parseFloat(seat.precio_adicional) || 0, // Asegura que el adicional sea número
                })),
                selectedSeats: [], // Array para guardar los IDs de los asientos seleccionados
                maxSeats: 6, // Límite de asientos a seleccionar

                // Función para seleccionar/deseleccionar un asiento
                toggleSeat(seat) {
                    // Solo permitir si el asiento está 'disponible'
                    if (seat.estado !== 'disponible') return; 

                    const index = this.selectedSeats.indexOf(seat.id);

                    if (index > -1) {
                        // Si ya está seleccionado, lo quitamos (deseleccionar)
                        this.selectedSeats.splice(index, 1);
                    } else {
                         // Si no está seleccionado, lo añadimos (seleccionar)
                         // Verificamos el límite
                        if (this.selectedSeats.length >= this.maxSeats) {
                            alert(`Solo puedes seleccionar un máximo de ${this.maxSeats} asientos.`);
                            return;
                        }
                        this.selectedSeats.push(seat.id);
                    }
                },

                // Propiedad computada: Devuelve los objetos completos de los asientos seleccionados
                get selectedSeatObjects() {
                    return this.asientos.filter(seat => this.selectedSeats.includes(seat.id));
                },

                // Propiedad computada: Calcula el precio total sumando base + adicional de cada asiento seleccionado
                get totalPrice() {
                    return this.selectedSeatObjects.reduce((sum, seat) => {
                        return sum + seat.precio_base + seat.precio_adicional;
                    }, 0);
                },

                // Función simple para formatear el precio a 2 decimales
                formatPrice(price) {
                    // Asegurarse que es un número antes de formatear
                    const numPrice = parseFloat(price);
                    return isNaN(numPrice) ? '0.00' : numPrice.toFixed(2);
                },

                // Función que se llamará al hacer clic en "Continuar"
                continuarReserva() {
                    // Por ahora, solo muestra una alerta. 
                    // Aquí es donde enviarías los datos al siguiente paso (Checkout)
                    const asientoIds = this.selectedSeats;
                    const asientoNumeros = this.selectedSeatObjects.map(s => s.numero_asiento).join(', ');
                    const total = this.totalPrice;

                    alert(`Asientos seleccionados (IDs): ${asientoIds.join(', ')}\nNúmeros: ${asientoNumeros}\nTotal: S/ ${this.formatPrice(total)} \n\nPróximo paso: Enviar estos datos al formulario de checkout.`);

                    // Ejemplo de cómo podrías enviar los datos (requiere un formulario POST):
                    // const form = document.createElement('form');
                    // form.method = 'POST';
                    // form.action = '/ruta/al/checkout'; // Define esta ruta
                    // const csrfInput = document.createElement('input');
                    // csrfInput.type = 'hidden';
                    // csrfInput.name = '_token';
                    // csrfInput.value = '{{ csrf_token() }}'; // Token CSRF
                    // form.appendChild(csrfInput);
                    // asientoIds.forEach(id => {
                    //     const input = document.createElement('input');
                    //     input.type = 'hidden';
                    //     input.name = 'asientos[]';
                    //     input.value = id;
                    //     form.appendChild(input);
                    // });
                    // document.body.appendChild(form);
                    // form.submit();
                }
            }));
        });
    </script>

</body>
</html>