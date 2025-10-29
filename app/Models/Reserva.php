<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'viaje_id',
        'user_id',
        'codigo_reserva',
        'nombre_comprador',
        'dni_comprador',
        'email_comprador',
        'telefono_comprador',
        'monto_total',
        'estado',
    ];

    // =======================================================
    // ¡¡ESTAS SON LAS RELACIONES QUE FALTAN!!
    // =======================================================

    /**
     * Obtiene el viaje asociado a la reserva.
     */
    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }

    /**
     * Obtiene el usuario (si existe) que hizo la reserva.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ¡¡ESTA ES LA FUNCIÓN QUE CAUSA EL ERROR!!
     * Obtiene los asientos/pasajeros asociados a esta reserva.
     */
    public function reservaAsientos()
    {
        // Esta función debe ser plural 'reservaAsientos'
        return $this->hasMany(ReservaAsiento::class);
    }
}