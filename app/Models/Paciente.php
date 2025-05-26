<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'id',
        'numero_historia',
        'nombre',
        'apellido',
        'documento',
        'fecha_nacimiento',
        'comunidad',
    ];
}
