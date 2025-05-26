<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Consulta extends Model
{
    use HasFactory;

    protected $fillable = ['paciente_id', 'fecha', 'motivo', 'examen_fisico'];


    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function diagnosticos()
    {
        return $this->belongsToMany(Diagnostico::class);
    }

    public function medicamentos()
    {
        return $this->belongsToMany(Medicamento::class);
    }
}
