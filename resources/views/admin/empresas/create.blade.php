<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Crear Nueva Empresa - Admin</title>

{{-- Estilos --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="icon" href="{{ asset('images/logo-pasajes24-7.png') }}" type="image/png">
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- =============================================== --}}
{{-- ESTILOS PARA EL DASHBOARD "MINIMALISTA PASTEL"  --}}
{{-- =============================================== --}}
<style>
    body { 
        font-family: 'Inter', sans-serif; 
        -webkit-font-smoothing: antialiased; 
        -moz-osx-font-smoothing: grayscale;
        background-color: #ffffff; /* Fondo principal blanco */
    }

    /* Sidebar: Un gris/azul sutil */
    .admin-sidebar { 
        background-color: #f1f5f9; /* bg-slate-100 */
        border-right: 1px solid #e5e7eb; /* border-r border-gray-200 */
    }
    
    /* Enlaces del Sidebar: Texto gris */
    .admin-sidebar a { 
        color: #4b5563; /* text-gray-600 */
        transition: all 0.2s ease-in-out;
    }
    .admin-sidebar a:hover { 
        background-color: #ffffff; /* bg-white */
        color: #111827; /* text-gray-900 */
    }
    
    /* Enlace Activo: Fondo blanco + acento naranja pastel */
    .admin-sidebar a.active { 
        color: #fb923c; /* text-orange-400 */
        background-color: #ffffff; /* bg-white */
        border-left: 4px solid #fb923c; /* border-l-4 border-orange-400 */
        padding-left: calc(1rem - 4px);
        font-weight: 600;
    }

    /* Título del Header limpio */
    .header-title {
         color: #111827; /* text-gray-900 */
         font-weight: 800;
         letter-spacing: -0.025em;
    }

    /* Estilos de Focus con Naranja Pastel */
    input:focus, select:focus, button:focus-visible { 
        outline: none; 
        box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.4); /* orange-400/40 */
        border-color: #fb923c !important; /* orange-400 */
    }
     /* Estilo para borde de input con error (opcional pero útil) */
     .input-error {
         border-color: #ef4444 !important; /* red-500 */
     }
     select.input-error { /* Específico para select */
         border-color: #ef4444 !important;
     }
</style>
</head>
<body class="bg-white text-gray-900">

<div class="flex min-h-screen">
    {{-- =============================================== --}}
    {{-- SIDEBAR (SLATE-100)                           --}}
    {{-- =============================================== --}}
    @include('admin.partials.sidebar')

    <div class="flex-1 flex flex-col md:ml-64">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 border-b border-gray-200 sticky top-0 z-40">
            <div class="flex items-center">
                <button class="md:hidden mr-4 text-gray-500 hover:text-gray-700"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                <h1 class="header-title text-xl font-bold text-gray-900">Crear Nueva Empresa</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Hola, {{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </header>

        <main class="flex-grow p-6 lg:p-10 bg-white">

            <div class="bg-slate-50 p-6 md:p-8 rounded-lg border border-slate-200 w-full max-w-3xl mx-auto">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-4 border-gray-200 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Detalles de la Empresa
                </h2>

                {{-- Mensajes de �?xito y Error --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg" role="alert">
                        <p class="font-bold">Por favor, corrige los siguientes errores:</p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulario para Creación de Empresas --}}
                <form action="{{ route('admin.empresas.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Fila 1: Nombre y RUC --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Empresa (*):</label>
                            <input type="text" name="nombre" id="nombre" required maxlength="100"
                                   value="{{ old('nombre') }}" placeholder="Ej: Perú Buses"
                                   class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm @error('nombre') input-error @enderror">
                            @error('nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="ruc" class="block text-sm font-medium text-gray-700 mb-1">RUC (*):</label>
                            <input type="text" name="ruc" id="ruc" required maxlength="15"
                                   value="{{ old('ruc') }}" placeholder="Ej: 20512345678"
                                   class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm @error('ruc') input-error @enderror">
                            @error('ruc') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Fila 2: Email y Teléfono de Contacto --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email_contacto" class="block text-sm font-medium text-gray-700 mb-1">Email de Contacto:</label>
                            <input type="email" name="email_contacto" id="email_contacto" maxlength="100"
                                   value="{{ old('email_contacto') }}" placeholder="Ej: ventas@empresa.com"
                                   class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm @error('email_contacto') input-error @enderror">
                            @error('email_contacto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="telefono_contacto" class="block text-sm font-medium text-gray-700 mb-1">Teléfono de Contacto:</label>
                            <input type="text" name="telefono_contacto" id="telefono_contacto" maxlength="20"
                                   value="{{ old('telefono_contacto') }}" placeholder="Ej: +51 987654321"
                                   class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm @error('telefono_contacto') input-error @enderror">
                            @error('telefono_contacto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Fila 3: URL del Logo --}}
                    <div>
                        <label for="logo_url" class="block text-sm font-medium text-gray-700 mb-1">URL del Logo (Opcional):</label>
                        <input type="url" name="logo_url" id="logo_url" maxlength="255"
                               value="{{ old('logo_url') }}" placeholder="Ej: https://midominio.com/logo.png"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm @error('logo_url') input-error @enderror">
                        @error('logo_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    {{-- Botón de envío --}}
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200 mt-8">
                        <a href="{{ route('admin.empresas.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 mr-4 transition duration-150">Cancelar</a>
                        <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg hover:from-orange-500 hover:to-orange-600 focus:outline-none focus:ring-offset-2 focus:ring-orange-400 transition duration-200 transform hover:-translate-y-0.5">
                            Guardar Empresa
                        </button>
                    </div>
                </form>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="bg-white text-gray-500 py-4 px-6 mt-auto border-t border-gray-200 text-sm text-center md:text-left">
            &copy; {{ date('Y') }} Pasajes24/7 - Panel de Administración.
        </footer>
    </div>
</div>

</body>
</html>


