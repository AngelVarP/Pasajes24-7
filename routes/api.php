<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\EmpresaDeTransporteController;
use App\Http\Controllers\CiudadController;

// Rutas de Rutas (para obtener, crear rutas)
Route::get('rutas', [RutaController::class, 'index']); // Obtener todas las rutas
Route::post('rutas', [RutaController::class, 'store']); // Crear una nueva ruta
Route::get('rutas/{id}', [RutaController::class, 'show']); // Obtener una ruta específica

// Rutas de Reservas (para obtener, crear reservas)
Route::get('reservas', [ReservaController::class, 'index']); // Obtener todas las reservas
Route::post('reservas', [ReservaController::class, 'store']); // Crear una nueva reserva
Route::get('reservas/{id}', [ReservaController::class, 'show']); // Obtener una reserva específica

// Rutas de Empresas de Transporte (para obtener, crear empresas)
Route::get('empresas', [EmpresaDeTransporteController::class, 'index']); // Obtener todas las empresas
Route::post('empresas', [EmpresaDeTransporteController::class, 'store']); // Crear una nueva empresa
Route::get('empresas/{id}', [EmpresaDeTransporteController::class, 'show']); // Obtener una empresa específica

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Rutas de Ciudades
    Route::get('/ciudades/buscar', [CiudadController::class, 'buscar']);

    // Rutas de Rutas
    Route::get('/rutas', [RutaController::class, 'index']);
    Route::post('/rutas', [RutaController::class, 'store']);
    Route::get('/rutas/{id}', [RutaController::class, 'show']);

    // Rutas de Reservas
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::post('/reservas', [ReservaController::class, 'store']);
    Route::get('/reservas/{id}', [ReservaController::class, 'show']);

    // Rutas de Empresas de Transporte
    Route::get('/empresas', [EmpresaDeTransporteController::class, 'index']);
    Route::post('/empresas', [EmpresaDeTransporteController::class, 'store']);
    Route::get('/empresas/{id}', [EmpresaDeTransporteController::class, 'show']);
});