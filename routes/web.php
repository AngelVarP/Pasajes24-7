<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ViajeAdminController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/ciudades/buscar', [CiudadController::class, 'buscar'])->name('ciudades.buscar');
Route::post('/buscar', [BusquedaController::class, 'buscar'])->name('viajes.buscar');

// Rutas de Autenticación para Admin
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
// Añadimos la ruta POST para procesar el login
Route::post('/admin/login', [LoginController::class, 'login']); 
// Añadimos la ruta POST para el logout
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout'); 

// Rutas de Administración (¡AHORA PROTEGIDAS!)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () { // <-- AÑADIDO MIDDLEWARE
    
    // Dashboard Básico (Crearemos esta vista después)
    Route::get('/', function () { 
        return view('admin.dashboard'); // Vista simple para el panel principal
    })->name('dashboard'); 
    
    // Rutas existentes de Viajes
    Route::get('/viajes/crear', [ViajeAdminController::class, 'create'])->name('viajes.create');
    Route::post('/viajes', [ViajeAdminController::class, 'store'])->name('viajes.store');
    
    // Aquí irán las rutas para listar, editar, eliminar viajes, gestionar rutas, etc.
});

