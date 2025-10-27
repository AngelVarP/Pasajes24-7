<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pasajes24/7 - Tu Aventura en Bus Comienza Aquí</title>
    <meta name="description" content="Diseña tu viaje perfecto. Encuentra y reserva pasajes de bus online con Pasajes24/7 de forma creativa y sencilla.">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        ::placeholder { color: #94a3b8; opacity: 1; } /* slate-400 */
        /* Custom focus ring */
        input:focus, button:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4); /* amber-500/40 */
            border-color: #f59e0b; /* amber-500 */
        }
        /* Clip path for hero background shape */
        .clip-path-fancy {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
        }
        @media (min-width: 768px) { /* md breakpoint */
            .clip-path-fancy {
                 clip-path: polygon(0 0, 100% 0, 100% 75%, 50% 100%, 0 75%);
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Navbar con fondo transparente inicial --}}
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-2">
                <a href="/" class="transition duration-150 drop-shadow-md">
                    <img src="{{ asset('images/logo-pasajes24-7-2.png') }}" alt="Logo Pasajes24/7" class="h-[180px] w-[180px]"> {{-- Ajusta la altura (h-10) según necesites --}}
                </a>
                </div>
        </div>
    </header>
    {{-- Hero Section con forma y superposición --}}
    <section class="relative bg-gradient-to-br from-gray-800 via-gray-900 to-black text-white pt-20 pb-24 md:pb-32 clip-path-fancy">
         {{-- Opcional: Imagen de fondo sutil con overlay oscuro --}}
         {{-- <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('URL_DE_IMAGEN_VIAJE.jpg');"></div> --}}
         {{-- <div class="absolute inset-0 bg-black opacity-60"></div> --}}

         <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16 md:pt-24 text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold mb-4 tracking-tight leading-tight">
                Tu Próximo Destino <span class="text-amber-400">te Espera</span>.
            </h1>
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto">Reserva pasajes de bus fácil y seguro. ¿A dónde vamos hoy?</p>
         </div>
    </section>

    {{-- Formulario de Búsqueda Flotante --}}
    <section class="relative z-20 -mt-20 md:-mt-28 pb-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div id="search-form-container" class="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-2xl shadow-xl border border-gray-100">
                <form action="/buscar" method="POST" class="space-y-5" id="search-form">
                     @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Campo Origen --}}
                        <div class="relative group">
                            <label for="origen" class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Origen</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg></div>
                                <input type="text" id="origen" name="origen" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-amber-500 transition duration-150 placeholder-gray-400 text-base" placeholder="Ingresa ciudad de origen" required>
                            </div>
                            <div id="origen-suggestions" class="absolute z-20 w-full bg-white border border-gray-200 rounded-b-lg shadow-lg mt-1 hidden max-h-52 overflow-y-auto text-left"></div>
                        </div>
                        {{-- Campo Destino --}}
                        <div class="relative group">
                             <label for="destino" class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Destino</label>
                             <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg></div>
                                <input type="text" id="destino" name="destino" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-amber-500 transition duration-150 placeholder-gray-400 text-base" placeholder="Ingresa ciudad de destino" required>
                            </div>
                            <div id="destino-suggestions" class="absolute z-20 w-full bg-white border border-gray-200 rounded-b-lg shadow-lg mt-1 hidden max-h-52 overflow-y-auto text-left"></div>
                        </div>
                    </div>
                     {{-- Campo Fecha --}}
                     <div class="relative group">
                         <label for="fecha" class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Fecha de Salida</label>
                         <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg></div>
                            <input type="date" id="fecha" name="fecha" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-amber-500 transition duration-150 text-gray-500 text-base" required min="{{ date('Y-m-d') }}">
                         </div>
                    </div>

                    <div id="search-error" class="text-red-600 text-sm pt-1 text-left hidden font-medium"></div>

                    {{-- Botón de Buscar con gradiente y efecto --}}
                    <button type="submit" class="w-full py-3 px-5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg font-semibold text-lg hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-offset-2 focus:ring-amber-500 transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Buscar Pasajes
                    </button>
                    <div class="mt-8 text-center">
                        <img src="{{ asset('images/autobus-moderno.png') }}" alt="Autobús moderno en carretera" class="mx-auto max-w-full h-auto rounded-lg shadow-md">
                    </div>
                </form>
            </div>
        </div>
    </section>

     {{-- Sección "Cómo Funciona" (Alternativa a Beneficios) --}}
     <section id="como-funciona" class="py-16 sm:py-20 bg-gray-50">
         <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
             <h2 class="text-3xl font-bold text-gray-800 mb-4">Reservar es Simple</h2>
             <p class="text-gray-500 mb-12 max-w-xl mx-auto">Sigue estos 3 pasos y estarás listo para tu viaje.</p>
             <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 relative">
                 {{-- Línea conectora (visual) --}}
                 <div class="hidden md:block absolute top-1/2 left-0 right-0 h-0.5 bg-gray-200 transform -translate-y-1/2 -mt-6" style="width: calc(100% - 10rem); left: 5rem;"></div>

                 {{-- Paso 1 --}}
                 <div class="relative z-10 text-center">
                     <div class="flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-600 mx-auto mb-4 border-4 border-white shadow-sm font-bold text-xl">1</div>
                     <h3 class="text-lg font-semibold text-gray-800 mb-2">Busca tu Viaje</h3>
                     <p class="text-gray-500 text-sm">Ingresa origen, destino y fecha en el formulario.</p>
                 </div>
                 {{-- Paso 2 --}}
                 <div class="relative z-10 text-center">
                     <div class="flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-600 mx-auto mb-4 border-4 border-white shadow-sm font-bold text-xl">2</div>
                     <h3 class="text-lg font-semibold text-gray-800 mb-2">Elige y Paga</h3>
                     <p class="text-gray-500 text-sm">Selecciona el horario y asiento que prefieras. Paga seguro online.</p>
                 </div>
                  {{-- Paso 3 --}}
                 <div class="relative z-10 text-center">
                     <div class="flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-600 mx-auto mb-4 border-4 border-white shadow-sm font-bold text-xl">3</div>
                     <h3 class="text-lg font-semibold text-gray-800 mb-2">¡Listo para Viajar!</h3>
                     <p class="text-gray-500 text-sm">Recibe tu pasaje digital y prepárate para la aventura.</p>
                 </div>
             </div>
         </div>
     </section>

    {{-- Footer Simple --}}
    <footer class="bg-gray-900 text-gray-400 py-10 mt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <nav class="flex justify-center space-x-6 mb-4 text-sm">
                <a href="#" class="hover:text-amber-400 transition duration-150">Ayuda</a>
                <a href="#" class="hover:text-amber-400 transition duration-150">Términos</a>
                <a href="#" class="hover:text-amber-400 transition duration-150">Privacidad</a>
            </nav>
            <p class="text-xs">&copy; {{ date('Y') }} Pasajes24/7. Creado con ❤️ para viajeros.</p>
        </div>
    </footer>

     {{-- Script para Navbar Transparente al inicio --}}
     <script>
        const navbar = document.getElementById('navbar');
        if (navbar) {
            const handleScroll = () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-gray-900', 'shadow-md');
                    navbar.classList.remove('bg-transparent');
                } else {
                    navbar.classList.remove('bg-gray-900', 'shadow-md');
                    navbar.classList.add('bg-transparent');
                }
            };
            window.addEventListener('scroll', handleScroll, { passive: true });
            // Ejecutar una vez al cargar por si la página carga ya scrolleada
            handleScroll();
        }

        // Script para menú móvil (si lo necesitas)
        // ... (el script del menú móvil anterior iría aquí) ...
    </script>

</body>
</html>

