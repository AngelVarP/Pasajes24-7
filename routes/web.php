<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\BusquedaController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/ciudades/buscar', [CiudadController::class, 'buscar'])->name('ciudades.buscar');
Route::post('/buscar', [BusquedaController::class, 'buscar'])->name('viajes.buscar');