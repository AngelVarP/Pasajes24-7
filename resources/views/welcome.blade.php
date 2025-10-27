<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pasajes24/7 - Tu viaje empieza aquí</title>
    {{-- Vite incluirá aquí app.css con Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Considera añadir Animate.css si quieres usar las clases animate__ --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/> --}}
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    {{-- Navbar (Ejemplo Básico) --}}
    <nav class="bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-teal-600">Pasajes24/7</a>
            <div>
                {{-- Aquí podrían ir enlaces de navegación (Login, Registro, Mis Viajes) --}}
                <a href="#" class="text-gray-600 hover:text-teal-600 px-3 py-2">Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    {{-- Sección Hero --}}
    <section class="w-full min-h-[calc(100vh-64px)] bg-gradient-to-r from-indigo-600 to-teal-500 flex flex-col justify-center items-center text-center p-6 md:p-8 relative overflow-hidden">
        {{-- Podrías añadir una imagen de fondo sutil aquí --}}
        <div class="absolute inset-0 bg-black opacity-20 z-0"></div>
        <div class="relative z-10">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight animate__animated animate__fadeInDown">Tu Próximo Viaje Comienza Aquí</h1>
            <p class="text-lg md:text-xl text-indigo-100 mb-8 max-w-3xl mx-auto animate__animated animate__fadeInUp animate__delay-1s">Encuentra y compra tus pasajes de bus fácil, rápido y seguro.</p>

            {{-- Formulario de Búsqueda --}}
            <div id="search-form-container" class="bg-white p-6 md:p-8 rounded-xl shadow-2xl w-full max-w-4xl mx-auto animate__animated animate__fadeInUp animate__delay-2s">
                {{-- Cambiado a POST y añadido @csrf para seguridad si se convierte en un form real --}}
                <form action="/buscar" method="POST" class="space-y-4 md:space-y-6" id="search-form">
                     @csrf {{-- Importante si el form envía datos al backend --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Campo Origen --}}
                        <div class="relative">
                            <label for="origen" class="block text-sm font-semibold text-gray-700 mb-1 text-left">Origen</label>
                            <input type="text" id="origen" name="origen" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition duration-150" placeholder="Ciudad de origen" required>
                            {{-- Contenedor para sugerencias de autocompletado --}}
                            <div id="origen-suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-b-lg shadow-lg mt-1 hidden"></div>
                        </div>
                        {{-- Campo Destino --}}
                        <div class="relative">
                            <label for="destino" class="block text-sm font-semibold text-gray-700 mb-1 text-left">Destino</label>
                            <input type="text" id="destino" name="destino" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition duration-150" placeholder="Ciudad de destino" required>
                             {{-- Contenedor para sugerencias de autocompletado --}}
                            <div id="destino-suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-b-lg shadow-lg mt-1 hidden"></div>
                        </div>
                    </div>
                    {{-- Campo Fecha --}}
                    <div class="w-full">
                        <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-1 text-left">Fecha de salida</label>
                        {{-- Usar tipo "text" y un datepicker JS es a menudo mejor UX que type="date" --}}
                        <input type="date" id="fecha" name="fecha" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition duration-150" required min="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Mensaje de Error (para JS) --}}
                    <div id="search-error" class="text-red-500 text-sm mt-2 text-left hidden"></div>

                    {{-- Botón de Buscar --}}
                    <button type="submit" class="w-full py-3 bg-teal-500 text-white rounded-lg font-semibold hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-300 shadow-lg transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        Buscar Pasajes
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Sección de Beneficios --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-10">¿Por qué elegir Pasajes24/7?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Beneficio 1 --}}
                <div class="p-6 bg-gray-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                     {{-- Icono SVG Ejemplo --}}
                     <svg class="w-12 h-12 mx-auto mb-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0c-1.657 0-3-.895-3-2s1.343-2 3-2 3-.895 3-2-1.343-2-3-2m0 .01H12m0-.01V6m0 16.03V20m0 2v-2.03m0-10a3 3 0 11-6 0 3 3 0 016 0zm-6 3a3 3 0 106 0 3 3 0 00-6 0zm12 0a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Precios Competitivos</h3>
                    <p class="text-gray-600">Encuentra las mejores ofertas en pasajes de bus con precios accesibles para todos.</p>
                </div>
                {{-- Beneficio 2 --}}
                 <div class="p-6 bg-gray-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                     {{-- Icono SVG Ejemplo --}}
                     <svg class="w-12 h-12 mx-auto mb-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Reserva Fácil y Rápida</h3>
                    <p class="text-gray-600">Compra tus pasajes en minutos, sin complicaciones, desde cualquier lugar.</p>
                </div>
                {{-- Beneficio 3 --}}
                 <div class="p-6 bg-gray-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                     {{-- Icono SVG Ejemplo --}}
                     <svg class="w-12 h-12 mx-auto mb-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Soporte al Cliente 24/7</h3>
                    <p class="text-gray-600">Estamos disponibles para ayudarte en cualquier momento. Tu experiencia es nuestra prioridad.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Sección de Llamada a la Acción --}}
    <section class="py-16 bg-teal-600 text-white text-center">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold mb-4">¡No esperes más, tu aventura te espera!</h2>
            <p class="text-lg mb-8">Encuentra tu próximo destino al mejor precio.</p>
            {{-- Enlaza a la página de búsqueda/resultados si ya existe --}}
            <a href="#search-form-container" class="bg-white text-teal-600 py-3 px-8 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 shadow-lg transform hover:scale-105 inline-block">Buscar Pasajes Ahora</a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-400 py-8 mt-16">
        <div class="container mx-auto text-center px-6">
            {{-- Podrías añadir enlaces útiles aquí (Sobre nosotros, Contacto, FAQ, etc.) --}}
            <p>&copy; {{ date('Y') }} Pasajes24/7. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>