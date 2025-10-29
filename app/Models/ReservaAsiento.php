<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaAsiento extends Model
{
    use HasFactory;

    /**
     * ¡¡CORREGIDO!!
     * Campos que se pueden asignar masivamente.
     */

    protected $table = 'reserva_asientos';
    
    protected $fillable = [
        'reserva_id',  // <--- ¡¡ESTA ERA LA LÍNEA QUE FALTABA!!
        'asiento_id',
        'viaje_id',
        'precio',
        'pasajero_nombre',
        'pasajero_dni',
        'pasajero_edad',
    ];

    // Relaciones (¡Importante!)

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function asiento()
    {
        return $this->belongsTo(Asiento::class);
    }

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }
}