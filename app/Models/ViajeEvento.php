<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViajeEvento extends Model
{
    use HasFactory;

    protected $table = 'viaje_eventos';

    protected $fillable = [
        'viaje_id',
        'estado_anterior',
        'estado_nuevo',
        'tipo_evento',
        'detalles',
        'actor_id',
        'actor_tipo',
    ];

    protected $casts = [
        'detalles' => 'array',
    ];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }
}
