<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';
    protected $fillable = [
        'viaje_id', 'user_id', 'codigo_reserva', 'monto_total', 'estado',
        'nombre_pasajero', 'dni_pasajero', 'email_pasajero', 'telefono_pasajero'
    ];

    // Relación: Una reserva pertenece a un viaje
    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }

    // Relación: Una reserva pertenece a un usuario (si está logueado)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con asientos (muchos a muchos a través de la tabla pivote)
    public function asientos()
    {
        return $this->belongsToMany(Asiento::class, 'reserva_asiento', 'reserva_id', 'asiento_id');
    }
}