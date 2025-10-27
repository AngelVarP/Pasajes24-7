<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'rutas'; // Nombre de la tabla
    protected $fillable = ['origen_id', 'destino_id', 'duracion_estimada_minutos'];

    // Relación: Una ruta tiene una ciudad de origen
    public function origen()
    {
        return $this->belongsTo(Ciudad::class, 'origen_id');
    }

    // Relación: Una ruta tiene una ciudad de destino
    public function destino()
    {
        return $this->belongsTo(Ciudad::class, 'destino_id');
    }

    // Relación: Una ruta tiene muchos viajes
    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'ruta_id');
    }
}