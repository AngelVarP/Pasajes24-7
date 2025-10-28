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


// Rutas protegidas por el middleware 'auth' y 'admin'
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

    // Dashboard
    Route::get('/', function () { 
        return view('admin.dashboard'); 
    })->name('dashboard'); 

    // Rutas de Viajes CRUD
    Route::get('/viajes', [ViajeAdminController::class, 'index'])->name('viajes.index'); // <--- ¡ASEGÚRATE DE QUE ESTA LÍNEA ESTÉ PRESENTE!
    Route::get('/viajes/crear', [ViajeAdminController::class, 'create'])->name('viajes.create');
    Route::post('/viajes', [ViajeAdminController::class, 'store'])->name('viajes.store');

    // Aquí irían las rutas para editar/eliminar que añadiremos más adelante.
    Route::get('/viajes/{viaje}/editar', [ViajeAdminController::class, 'edit'])->name('viajes.edit');
    Route::put('/viajes/{viaje}', [ViajeAdminController::class, 'update'])->name('viajes.update');

    // ⭐ RUTA NUEVA para Cancelar un Viaje ⭐
    Route::post('/viajes/{viaje}/cancelar', [ViajeAdminController::class, 'cancelar'])->name('viajes.cancelar');
    // ... (otras rutas si las tienes) ...
});