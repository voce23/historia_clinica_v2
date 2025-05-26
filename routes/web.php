<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pacientes\Index;
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
    Route::get('/pacientes', Index::class)->name('pacientes.index');
});

// para descargar word

Route::get('/export-pacientes-docx', [ExportController::class, 'exportPacientesDocx'])->name('export.pacientes.docx');

require __DIR__.'/auth.php';
