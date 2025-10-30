<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ruta; // ¡Asegúrate de que esta línea exista!

class Ciudad extends Model
{
    use HasFactory;

    /**
     * Especifica el nombre de la tabla de la base de datos,
     * corrigiendo la pluralización automática de Laravel.
     *
     * @var string
     */
    protected $table = 'ciudades'; // ¡ESTA ES LA LÍNEA CLAVE!



    // Asegúrate de que los campos que quieres modificar están en $fillable
    protected $fillable = [
        'nombre',
        'departamento',
        // ... otros campos si tienes
    ];

    /**
     * Define la relación: Rutas donde esta ciudad es el origen.
     * Una Ciudad tiene muchas Rutas de Origen.
     */
    public function rutasOrigen()
    {
        // En este caso, la clave foránea en la tabla 'rutas' es 'origen_id'
        return $this->hasMany(Ruta::class, 'origen_id');
    }

    /**
     * Define la relación: Rutas donde esta ciudad es el destino.
     * Una Ciudad tiene muchas Rutas de Destino.
     */
    public function rutasDestino()
    {
        // En este caso, la clave foránea en la tabla 'rutas' es 'destino_id'
        return $this->hasMany(Ruta::class, 'destino_id');
    }
}