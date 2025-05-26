<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Export example using User model.
 */
class ExampleExport implements FromCollection
{
    /**
     * Return all users for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all();
    }
}
