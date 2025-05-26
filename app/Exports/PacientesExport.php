<?php

namespace App\Exports;

use App\Models\Paciente;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Exporta todos los pacientes a Excel.
 */
class PacientesExport implements FromCollection
{
    /**
     * Retorna todos los pacientes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Paciente::all();
    }
}
