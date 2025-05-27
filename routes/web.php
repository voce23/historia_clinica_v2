<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios\Index;
use App\Livewire\Pacientes\Index as PacientesIndex;
use App\Livewire\Diagnosticos\Index as DiagnosticosIndex;
use App\Livewire\Medicamentos\Index as MedicamentosIndex;
use App\Livewire\Consultas\Index as ConsultasIndex;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ConsultaExportController;


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

Route::middleware(['auth'])->group(function () {
    Route::get('/consultas', ConsultasIndex::class)->name('consultas.index');
});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/usuarios', Index::class)->name('usuarios.index');
});


// para descargar word

Route::get('/export-pacientes-docx', [ExportController::class, 'exportPacientesDocx'])->name('export.pacientes.docx');


Route::get('/consultas/{id}/exportar-word', [ConsultaExportController::class, 'exportarWord'])->name('consultas.exportarWord');









require __DIR__.'/auth.php';
