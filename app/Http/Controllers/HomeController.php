<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $rutas = Ruta::latest()->take(3)->get(); // Obtiene las 3 rutas más recientes
        return view('welcome', compact('rutas'));
    }
}
