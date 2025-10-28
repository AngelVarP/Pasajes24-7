<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importar Facade Auth
use Illuminate\Validation\ValidationException; // Para manejar errores de login

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login de administrador.
     */
    public function showLoginForm()
    {
        // Si el admin ya está logueado, redirige al dashboard
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login'); 
    }

    /**
     * Maneja el intento de login del administrador.
     */
    public function login(Request $request)
    {
        // 1. Validar los datos de entrada
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentar autenticar al usuario con las credenciales
        if (Auth::attempt($credentials, $request->boolean('remember'))) { // Añadido 'remember' opcional
            
            // 3. Verificar si el usuario autenticado es administrador
            if (Auth::user()->is_admin) {
                $request->session()->regenerate(); // Regenerar sesión por seguridad
                // Redirigir al dashboard de admin (o a donde intentaba ir antes)
                return redirect()->intended(route('admin.dashboard')); 
            } else {
                // Si NO es admin, aunque las credenciales sean correctas:
                Auth::logout(); // Desloguear
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                // Devolver error específico
                throw ValidationException::withMessages([
                    'email' => __('auth.admin_only'), // Mensaje de error personalizado (puedes añadirlo en lang/es/auth.php)
                ])->redirectTo(route('admin.login'));
            }
        }

        // 4. Si la autenticación (email/password) falla
        throw ValidationException::withMessages([
            'email' => __('auth.failed'), // Mensaje de error estándar de Laravel
        ])->redirectTo(route('admin.login'));
    }

    /**
     * Cierra la sesión del administrador.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario actual

        $request->session()->invalidate(); // Invalida la sesión actual
        $request->session()->regenerateToken(); // Regenera el token CSRF

        return redirect('/'); // Redirige a la página principal
    }
}