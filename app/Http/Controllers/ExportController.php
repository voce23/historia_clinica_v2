<?php

namespace App\Http\Controllers;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\File;

class ExportController extends Controller
{
    public function exportPacientesDocx()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addTitle('Módulo: Pacientes', 1);
        $section->addText('Este documento contiene la estructura del módulo Pacientes: modelo, componente Livewire y vista.', ['size' => 12]);

        // Agregar contenido de los archivos si existen
        $paths = [
            'Modelo Paciente' => app_path('Models/Paciente.php'),
            'Componente Livewire' => app_path('Livewire/Pacientes/Index.php'),
            'Vista Blade' => resource_path('views/livewire/pacientes/index.blade.php'),
        ];

        foreach ($paths as $title => $path) {
            $section->addTitle($title, 2);
            if (File::exists($path)) {
                $code = File::get($path);
                $section->addText(htmlspecialchars($code), ['name' => 'Courier New', 'size' => 10]);
            } else {
                $section->addText('Archivo no encontrado: ' . $path);
            }
        }

        $filename = 'modulo_pacientes.docx';
        $tempPath = tempnam(sys_get_temp_dir(), $filename);
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}
