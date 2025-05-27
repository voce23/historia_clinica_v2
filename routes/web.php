<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios\Index as UsuariosIndex;
use App\Livewire\Pacientes\Index as PacientesIndex;
use App\Livewire\Diagnosticos\Index as DiagnosticosIndex;
use App\Livewire\Medicamentos\Index as MedicamentosIndex;
use App\Livewire\Consultas\Index as ConsultasIndex;
use App\Livewire\Establecimientos\Index as EstablecimientosIndex;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ConsultaExportController;
use App\Livewire\AvisoEnConstruccion;

// Página pública
Route::view('/', 'welcome');

// Panel principal
Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');
Route::view('/profile', 'profile')->middleware(['auth'])->name('profile');

// Módulos de uso general (autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/pacientes', PacientesIndex::class)->name('pacientes.index');
    Route::get('/diagnosticos', DiagnosticosIndex::class)->name('diagnosticos.index');
    Route::get('/medicamentos', MedicamentosIndex::class)->name('medicamentos.index');
    Route::get('/consultas', ConsultasIndex::class)->name('consultas.index');
});

// Módulos exclusivos para administrador
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/usuarios', UsuariosIndex::class)->name('usuarios.index');
    Route::get('/establecimientos', EstablecimientosIndex::class)->name('establecimientos.index');
});

// Exportaciones de documentos
Route::get('/export-pacientes-docx', [ExportController::class, 'exportPacientesDocx'])->name('export.pacientes.docx');
Route::get('/consultas/{id}/exportar-word', [ConsultaExportController::class, 'exportarWord'])->name('consultas.exportarWord');

Route::get('/en-construccion', AvisoEnConstruccion::class)->name('construccion');

// Archivos de autenticación
require __DIR__.'/auth.php';
