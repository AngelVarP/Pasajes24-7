<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/ciudades/buscar', [CiudadController::class, 'buscar'])->name('ciudades.buscar');
Route::post('/buscar', [BusquedaController::class, 'buscar'])->name('viajes.buscar');
// Ruta para el formulario de login de administrador
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login'); // <-- ¡AÑADE ESTA LÍNEA!

// Rutas de Administración (Por ahora sin protección, la añadiremos después)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/viajes/crear', [ViajeAdminController::class, 'create'])->name('viajes.create');
    // Aquí irán más rutas de admin luego...
});