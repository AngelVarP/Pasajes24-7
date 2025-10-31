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

    {{-- =============================================== --}}
    {{-- ESTILOS PARA EL LOGIN "MINIMALISTA PASTEL"      --}}
    {{-- =============================================== --}}
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale; 
            /* Fondo gris/azul sutil como el sidebar */
            background-color: #f1f5f9; /* bg-slate-100 */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        /* Estilos de Focus con Naranja Pastel */
        input:focus, button:focus-visible { 
            outline: none; 
            box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.4); /* orange-400/40 */
            border-color: #fb923c !important; /* orange-400 */
        }
    </style>
</head>
<body class="text-gray-900">

    {{-- Contenedor principal centrado --}}
    <div class="w-full max-w-md px-4 sm:px-0">
        
        {{-- Card de Login --}}
        <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg w-full space-y-6 border border-gray-200">
            
            {{-- Logo --}}
            <a href="/" class="mb-4 block text-center">
                <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-20 w-auto mx-auto">
            </a>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-1 text-center">Acceso Administrador</h1>
            <p class="text-center text-gray-500 text-sm mb-6">Bienvenido de nuevo.</p>

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm w-full" role="alert">
                    {{ $errors->first('email') }}
                </div>
            @endif
             @if (session('error'))
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm w-full" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('admin.login') }}" class="w-full space-y-5 pt-2"> 
                @csrf

                {{-- Campo Correo Electrónico (Estilo minimalista) --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-md shadow-sm sm:text-sm h-11">
                </div>

                {{-- Campo Contraseña (Estilo minimalista) --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-md shadow-sm sm:text-sm h-11">
                </div>
                
                {{-- Botón Iniciar Sesión --}}
                <div class="pt-3">
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg text-white bg-gradient-to-r from-orange-400 to-orange-500 hover:shadow-lg hover:from-orange-500 hover:to-orange-600 focus:outline-none transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            {{-- Enlaces de footer --}}
            <div class="text-center text-gray-500 text-xs mt-6 pt-4 border-t border-gray-200">
                <a href="/" class="block text-sm font-medium text-gray-500 hover:text-orange-500 transition duration-150">
                    &larr; Volver a la web
                </a>
                <p class="mt-4">&copy; {{ date('Y') }} Pasajes24/7.</p>
            </div>
        </div>
    </div>
</body>
</html>