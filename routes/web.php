<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ViajeAdminController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RutaAdminController; // <-- Importante
use App\Http\Controllers\CiudadAdminController; // <-- Importante
use App\Http\Controllers\ReservaAdminController;

// Ruta principal (Homepage) AHORA SÍ TIENE NOMBRE
Route::get('/', [HomeController::class, 'index'])->name('home'); 

Route::get('/ciudades/buscar', [CiudadController::class, 'buscar'])->name('ciudades.buscar');
Route::post('/buscar', [BusquedaController::class, 'buscar'])->name('viajes.buscar');

// --- RUTAS PÚBLICAS DE RESERVA ---
// Ruta para MOSTRAR la selección de asientos
Route::get('/viajes/{viaje}/asientos', [BusquedaController::class, 'mostrarAsientos'])->name('viajes.asientos');

Route::post('/reservar/store', [App\Http\Controllers\ReservaController::class, 'store'])
     ->name('reservas.store');

// PASO 2 (NUEVA RUTA GET): Mostrar el formulario de pasajeros
Route::get('/reservar/ingresar-datos', [App\Http\Controllers\ReservaController::class, 'mostrarPasajeros'])
     ->name('reservas.mostrar_pasajeros');

Route::post('/reservar/procesar-pasajeros', [App\Http\Controllers\ReservaController::class, 'procesarPasajeros'])
     ->name('reservas.procesar_pasajeros'); // <-- Coincide con reserva_pasajeros.blade.php

// Paso 3: Página de éxito/confirmación
Route::get('/reservar/confirmacion/{reserva}', [App\Http\Controllers\ReservaController::class, 'confirmacion'])
     ->name('reserva.confirmacion');
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
    Route::resource('rutas', App\Http\Controllers\RutaAdminController::class)->except(['show']); // Asegúrate de incluir el namespace completo si es necesario
    Route::resource('ciudades', App\Http\Controllers\CiudadAdminController::class)->parameters([
    'ciudades' => 'ciudad',
     ]    );

     // Rutas de Reporte de Reservas (NUEVA RUTA)
    Route::get('/reservas', [ReservaAdminController::class, 'index'])->name('reservas.index');



});
// (Y ya no tiene la llave "}" extra que tenías al final)