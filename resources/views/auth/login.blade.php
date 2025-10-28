<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Administrador - Pasajes24/7</title>
    <meta name="description" content="Iniciar sesión en el panel de administración de Pasajes24/7.">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale; 
            overflow: hidden; 
            position: relative; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh;
        }
        ::placeholder { color: transparent; }
        
        input:focus, button:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4); 
            border-color: #f59e0b !important; 
        }
        
        /* CAMBIO: Fondo con gradiente animado azul-naranja */
        .animated-gradient-background {
            position: fixed;
            inset: 0;
            background: linear-gradient(-45deg, #a7d9f7, #66a1d2, #f97316, #f59e0b); /* Azules claros a ámbar/naranja */
            background-size: 400% 400%;
            animation: gradientAnimation 18s ease infinite; /* Animación más lenta */
            z-index: -1; 
            filter: blur(8px); /* Más desenfoque para suavizar la transición de color */
            opacity: 0.8; 
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* ESTILOS PARA FLOATING LABELS (ajustados para el nuevo fondo del card) */
        .floating-label-input {
            position: relative;
        }
        .floating-label-input label {
            position: absolute;
            top: 50%; 
            left: 1rem; 
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8; /* slate-400, un gris azulado sutil */
            transition: all 0.2s ease-out;
            font-size: 1rem; 
            background-color: transparent; 
            padding: 0 0.25rem;
            z-index: 10;
        }
        .floating-label-input input:focus + label,
        .floating-label-input input:not(:placeholder-shown) + label {
            top: 0; 
            transform: translateY(-50%); 
            left: 0.75rem; 
            font-size: 0.75rem; 
            color: #d97706; /* amber-600 */
            /* CAMBIO: Fondo de la label para coincidir con el nuevo bg del card */
            background-color: #f0f8ff; /* Un azul muy claro, similar al card */ 
            padding: 0 0.5rem;
        }
        .floating-label-input input {
             padding-top: 1.5rem; 
             padding-bottom: 0.5rem; 
        }
    </style>
</head>
<body class="text-gray-900">

    {{-- Fondo animado con gradiente azul-naranja --}}
    <div class="animated-gradient-background"></div>

    {{-- Contenedor principal centrado --}}
    <div class="relative z-10 w-full max-w-md px-4 sm:px-0">
        
        {{-- Card de Login --}}
        {{-- CAMBIO: bg-slate-50 a bg-gradient-to-br from-white to-blue-50 (blanco a azul muy claro) --}}
        <div class="bg-gradient-to-br from-white to-blue-50 p-8 sm:p-10 rounded-2xl shadow-2xl w-full space-y-6 fade-in-up border-b-4 border-amber-500"> 
            
            {{-- Logo --}}
            <a href="/" class="mb-8 block text-center">
                <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-20 w-auto mx-auto mb-4">
            </a>
            
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2 text-center">Acceso Administrador</h1>
            <p class="text-center text-gray-600 text-sm mb-8">Bienvenido de nuevo.</p>

            {{-- Línea decorativa --}}
            <div class="w-20 h-1 mx-auto bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mb-8"></div>

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded text-sm w-full mb-4" role="alert">
                    {{ $errors->first('email') }}
                </div>
            @endif
             @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded text-sm w-full mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('admin.login') }}" class="w-full space-y-6 pt-2"> 
                @csrf

                {{-- Campo Correo Electrónico con Floating Label --}}
                <div class="floating-label-input">
                    <input id="email" name="email" type="email" autocomplete="email" required placeholder=" " 
                           class="block w-full px-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition duration-200 h-14 bg-white"> {{-- Asegurar que el input sea blanco --}}
                    <label for="email">Correo Electrónico</label>
                </div>

                {{-- Campo Contraseña con Floating Label --}}
                <div class="floating-label-input">
                    <input id="password" name="password" type="password" autocomplete="current-password" required placeholder=" " 
                           class="block w-full px-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition duration-200 h-14 bg-white"> {{-- Asegurar que el input sea blanco --}}
                    <label for="password">Contraseña</label>
                </div>
                
                {{-- Botón Iniciar Sesión --}}
                <div class="pt-3">
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:shadow-lg hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            {{-- Enlaces de footer --}}
            <div class="text-center text-gray-500 text-xs mt-6 pt-4 border-t border-gray-200">
                <p>&copy; {{ date('Y') }} Pasajes24/7.</p>
                <div class="mt-2 space-x-3">
                    <a href="#" class="hover:text-amber-600 transition duration-150">Términos</a>
                    <span>&bull;</span>
                    <a href="#" class="hover:text-amber-600 transition duration-150">Privacidad</a>
                </div>
                 <a href="/" class="block mt-4 text-sm font-medium text-gray-500 hover:text-amber-600 transition duration-150"> &larr; Volver a la web </a>
            </div>
        </div>
    </div>
</body>
</html>