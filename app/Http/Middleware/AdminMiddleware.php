<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importa el Facade de Autenticación
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verifica si el usuario está autenticado
        if (!Auth::check()) {
            // Si no está autenticado, redirige al login de admin
            return redirect()->route('admin.login');
        }

        // 2. Verifica si el usuario autenticado es administrador
        if (!Auth::user()->is_admin) {
            // Si no es admin, cierra su sesión (por seguridad) y redirige a la home con un error
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('error', 'Acceso no autorizado.'); // Puedes mostrar este error en welcome.blade.php
        }

        // 3. Si está autenticado y es admin, permite continuar con la solicitud
        return $next($request);
    }
}