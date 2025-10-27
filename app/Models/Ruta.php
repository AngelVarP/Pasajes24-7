<?php

// app/Models/Ruta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $fillable = [
        'origen',
        'destino',
        'fecha',
        'hora_salida',
        'hora_llegada',
    ];
}

