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
    <link rel="icon" href="{{ asset('images/p-favicon.png') }}" type="image/png"> {{-- Simplificado --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        ::placeholder { color: #94a3b8; opacity: 1; } /* slate-400 */
        /* Custom focus ring - Amber */
        input:focus, button:focus-visible, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4); /* amber-500/40 */
            border-color: #f59e0b; /* amber-500 */
        }
        /* Sutil patrón de fondo (opcional) */
        /* body { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); } */
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    {{-- Navbar (Sólido para páginas internas/admin) --}}
    <header class="bg-gray-900 shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="transition duration-150 drop-shadow-md">
                    <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-[60px] w-auto">
                </a>
                <a href="{{ route('admin.login') }}" class="text-sm font-medium bg-white text-gray-800 px-4 py-2 rounded-full shadow-sm hover:bg-gray-200 transition duration-150 flex items-center login-button">
                    <svg class="w-5 h-5 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span>Acceso Admin</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Contenido principal de la página de Login --}}
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 sm:p-10 rounded-xl shadow-2xl w-full max-w-md space-y-6 border border-gray-200">
            {{-- Logo dentro del card --}}
            <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-16 w-auto mx-auto mb-4">
            
            <h1 class="text-2xl font-bold text-gray-900 text-center">Acceso Administrador</h1>

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded" role="alert">
                    <p>{{ $errors->first('email') }}</p>
                </div>
            @endif
             @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                {{-- Campo Email --}}
                <div class="relative">
                     {{-- Icono --}}
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <label for="email" class="sr-only">Correo Electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           placeholder="Correo Electrónico"
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                </div>

                {{-- Campo Contraseña --}}
                 <div class="relative">
                     {{-- Icono --}}
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <label for="password" class="sr-only">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           placeholder="Contraseña"
                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                </div>
                
                 {{-- Opcional: Recordarme y Olvidó contraseña --}}
                {{-- <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900"> Recordarme </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-amber-600 hover:text-amber-500"> ¿Olvidaste tu contraseña? </a>
                    </div>
                </div> --}}

                {{-- Botón Ingresar --}}
                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150 ease-in-out">
                        Ingresar
                    </button>
                </div>
            </form>

            {{-- Enlace opcional para volver a la home --}}
            <div class="text-center text-sm mt-6">
                 <a href="/" class="font-medium text-gray-500 hover:text-gray-700"> &larr; Volver a la página principal </a>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-10 mt-auto">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-6">
                 <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-8 w-auto mx-auto opacity-75 hover:opacity-100 transition duration-150">
            </div>
            <nav class="flex justify-center space-x-6 mb-4 text-sm">
                <a href="#" class="hover:text-amber-400 transition duration-150">Ayuda</a>
                <a href="#" class="hover:text-amber-400 transition duration-150">Términos</a>
                <a href="#" class="hover:text-amber-400 transition duration-150">Privacidad</a>
            </nav>
            <p class="text-xs">&copy; {{ date('Y') }} Pasajes24/7. Creado con ❤️ para viajeros.</p>
        </div>
    </footer>

</body>
</html>