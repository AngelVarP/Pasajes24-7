<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router; // <-- ¡Asegúrate de importar Router!

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Aquí es donde registramos nuestro middleware
     */
    public function boot(Router $router): void // <-- Inyecta el Router aquí
    {
        // Registra tu middleware con el alias 'admin'
        $router->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
    }
}