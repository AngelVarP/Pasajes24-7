<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Viaje extends Model
{
    use HasFactory;

    protected $table = 'viajes'; // Nombre de la tabla
    protected $fillable = [
        'ruta_id',
        'empresa_id',
        'fecha_salida',
        'hora_salida',
        'precio',
        'asientos_totales',
        'asientos_disponibles',
        'tipo_servicio',
        'estado',
        'hora_llegada_real',
        'minutos_retraso',
    ];

    protected $casts = [
        'hora_llegada_real' => 'datetime',
    ];

    // Relación: Un viaje pertenece a una ruta
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    // Relación: Un viaje pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(EmpresaDeTransporte::class, 'empresa_id');
    }

    // Relación: Un viaje tiene muchos asientos
    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'viaje_id');
    }

    // Relación: Un viaje tiene muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'viaje_id');
    }

    public function eventos()
    {
        return $this->hasMany(ViajeEvento::class);
    }
}
