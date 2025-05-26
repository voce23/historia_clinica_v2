<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pacientes\Index as PacientesIndex;
use App\Livewire\Diagnosticos\Index as DiagnosticosIndex;
use App\Livewire\Medicamentos\Index as MedicamentosIndex;
use App\Http\Controllers\ExportController;


Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



// mios

Route::middleware(['auth'])->group(function () {
    Route::get('/pacientes', PacientesIndex::class)->name('pacientes.index');
    Route::get('/diagnosticos', DiagnosticosIndex::class)->name('diagnosticos.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/medicamentos', MedicamentosIndex::class)->name('medicamentos.index');
});




// para descargar word

Route::get('/export-pacientes-docx', [ExportController::class, 'exportPacientesDocx'])->name('export.pacientes.docx');









require __DIR__.'/auth.php';
