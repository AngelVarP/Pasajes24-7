<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pasajes24/7</title>
    @vite('resources/js/app.js') <!-- Incluir archivo JS compilado por Vite -->
    <!-- Aquí Tailwind CSS ya está siendo incluido a través de Vite -->
</head>
<body class="bg-gray-50 text-gray-900 font-sans">

    <!-- Sección Hero -->
    <section class="w-full min-h-screen bg-gradient-to-r from-indigo-500 to-teal-400 flex flex-col justify-center items-center text-center p-8">
        <h1 class="text-6xl font-extrabold text-white mb-4 animate__animated animate__fadeIn">¡Bienvenido a Pasajes24/7!</h1>
        <p class="text-xl text-white mb-8 animate__animated animate__fadeIn animate__delay-1s">Compra tus pasajes de bus de manera rápida, segura y accesible. ¡Viaja cómodo, viaja con Pasajes24/7!</p>
        
        <!-- Formulario de Búsqueda -->
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-4xl animate__animated animate__fadeIn animate__delay-2s">
            <form action="#" method="GET" class="space-y-6">
                <div class="flex gap-4 items-center">
                    <!-- Campo Origen -->
                    <div class="w-full">
                        <label for="origen" class="block text-sm font-semibold text-gray-700">Origen</label>
                        <input type="text" id="origen" name="origen" class="w-full p-3 border border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Ciudad de origen">
                    </div>
                    <!-- Campo Destino -->
                    <div class="w-full">
                        <label for="destino" class="block text-sm font-semibold text-gray-700">Destino</label>
                        <input type="text" id="destino" name="destino" class="w-full p-3 border border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Ciudad de destino">
                    </div>
                </div>
                <!-- Campo Fecha -->
                <div class="w-full">
                    <label for="fecha" class="block text-sm font-semibold text-gray-700">Fecha de salida</label>
                    <input type="date" id="fecha" name="fecha" class="w-full p-3 border border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Botón de Buscar -->
                <button type="submit" class="w-full py-3 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-300 shadow-lg">Buscar Pasajes</button>
            </form>
        </div>
    </section>

    <!-- Sección de Beneficios -->
    <section class="w-full py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">¿Por qué elegir Pasajes24/7?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
                <!-- Beneficio 1 -->
                <div class="p-6 bg-white rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
                    <h3 class="text-xl font-semibold text-teal-500 mb-4">Precios Competitivos</h3>
                    <p>Encuentra las mejores ofertas en pasajes de bus con precios accesibles para todos.</p>
                </div>
                <!-- Beneficio 2 -->
                <div class="p-6 bg-white rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
                    <h3 class="text-xl font-semibold text-teal-500 mb-4">Reserva Fácil y Rápida</h3>
                    <p>Compra tus pasajes en minutos, sin complicaciones, desde cualquier lugar.</p>
                </div>
                <!-- Beneficio 3 -->
                <div class="p-6 bg-white rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300">
                    <h3 class="text-xl font-semibold text-teal-500 mb-4">Soporte al Cliente 24/7</h3>
                    <p>Estamos disponibles para ayudarte en cualquier momento. Tu experiencia es nuestra prioridad.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Llamada a la Acción -->
    <section class="w-full py-16 bg-teal-500 text-white text-center">
        <h2 class="text-3xl font-bold mb-4">¡Reserva ahora y empieza tu viaje!</h2>
        <p class="text-lg mb-6">Encuentra tu próximo destino al mejor precio, de manera rápida y segura.</p>
        <a href="#" class="bg-white text-teal-500 py-3 px-6 rounded-lg font-semibold hover:bg-teal-600 hover:text-white transition duration-300">Explorar Pasajes</a>
    </section>

    <!-- Footer -->
    <footer class="w-full bg-gray-800 text-white py-6 mt-20">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 Pasajes24/7. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
