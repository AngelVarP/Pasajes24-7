<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;

    protected $table = 'asientos';
    protected $fillable = ['viaje_id', 'numero_asiento', 'piso', 'estado', 'precio_adicional'];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }

    // Relación con reservas (muchos a muchos a través de la tabla pivote)
    public function reservas()
    {
        return $this->belongsToMany(Reserva::class, 'reserva_asiento', 'asiento_id', 'reserva_id');
    }
}