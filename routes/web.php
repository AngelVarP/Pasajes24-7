<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ViajeAdminController;
use App\Http\Controllers\ReservaController; // <-- Importante

// Ruta principal (Homepage) AHORA SÍ TIENE NOMBRE
Route::get('/', [HomeController::class, 'index'])->name('home'); 

Route::get('/ciudades/buscar', [CiudadController::class, 'buscar'])->name('ciudades.buscar');
Route::post('/buscar', [BusquedaController::class, 'buscar'])->name('viajes.buscar');

// --- RUTAS PÚBLICAS DE RESERVA ---
// Ruta para MOSTRAR la selección de asientos
Route::get('/viajes/{viaje}/asientos', [BusquedaController::class, 'mostrarAsientos'])->name('viajes.asientos');

// Ruta para PROCESAR la selección (la que usa el formulario)
Route::post('/reservar/store', [ReservaController::class, 'store'])->name('reservas.store');
// ---------------------------------


// Rutas de Autenticación para Admin
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login']); 
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
    Route::get('/viajes', [ViajeAdminController::class, 'index'])->name('viajes.index');
    Route::get('/viajes/crear', [ViajeAdminController::class, 'create'])->name('viajes.create');
    Route::post('/viajes', [ViajeAdminController::class, 'store'])->name('viajes.store');
    Route::get('/viajes/{viaje}/editar', [ViajeAdminController::class, 'edit'])->name('viajes.edit');
    Route::put('/viajes/{viaje}', [ViajeAdminController::class, 'update'])->name('viajes.update');
    Route::post('/viajes/{viaje}/cancelar', [ViajeAdminController::class, 'cancelar'])->name('viajes.cancelar');
    Route::post('/viajes/actualizar-estados', [App\Http\Controllers\ViajeAdminController::class, 'actualizarEstadosManualmente'])->name('viajes.actualizarEstados');
});
// (Y ya no tiene la llave "}" extra que tenías al final)