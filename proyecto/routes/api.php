<?php

// routes/api.php

use App\Http\Controllers\RutaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\EmpresaDeTransporteController;
use Illuminate\Support\Facades\Route;

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
