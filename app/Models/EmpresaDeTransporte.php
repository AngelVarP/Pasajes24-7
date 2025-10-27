<?php

// app/Models/EmpresaDeTransporte.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaDeTransporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'contacto',
    ];
}
