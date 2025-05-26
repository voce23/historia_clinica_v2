<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Paciente extends Model
{

    //use HasFactory;

    protected $fillable = [
        'id',
        'numero_historia',
        'nombre',
        'apellido',
        'documento',
        'fecha_nacimiento',
        'comunidad',
    ];

    public function getEdadAttribute(): string
    {
        $nacimiento = Carbon::parse($this->fecha_nacimiento);
        $hoy = Carbon::now();

        $diff = $nacimiento->diff($hoy);

        if ($diff->y < 1) {
            return "{$diff->m} meses y {$diff->d} días";
        }

        return "{$diff->y} años y {$diff->m} meses";
    }
}
