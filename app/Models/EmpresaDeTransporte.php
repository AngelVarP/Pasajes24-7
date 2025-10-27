<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaDeTransporte extends Model
{
    use HasFactory;

    protected $table = 'empresas_de_transporte'; // Nombre de la tabla
    protected $fillable = ['nombre', 'ruc', 'logo_url', 'email_contacto', 'telefono_contacto'];

    // Relación: Una empresa tiene muchos viajes
    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'empresa_id');
    }

    // Relación: Una empresa puede tener muchas rutas (indirectamente a través de viajes, o directamente si defines)
    public function rutas()
    {
        return $this->hasManyThrough(Ruta::class, Viaje::class, 'empresa_id', 'id', 'id', 'ruta_id');
    }
}