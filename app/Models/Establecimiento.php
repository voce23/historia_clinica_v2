<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa un centro de salud o establecimiento.
 */
class Establecimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo',
        'municipio',
        'activo',
    ];
}
