<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;



class ConsultaExportController extends Controller
{

    public function exportarWord($id)
    {
        $consulta = Consulta::with(['paciente', 'diagnosticos', 'medicamentos'])->findOrFail($id);

        $phpWord = new PhpWord();
        // Definir estilo compacto para texto con poco espacio entre líneas
        $phpWord->addParagraphStyle('compact', [
            'spaceBefore' => 0,
            'spaceAfter' => 0,
            'lineHeight' => 1.6,
        ]);

        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(9); // más pequeño para una sola hoja

        $section = $phpWord->addSection([
            'paperSize' => 'Letter',
            'marginTop' => 900,
            'marginRight' => 500,
            'marginBottom' => 400,
            'marginLeft' => 900,
        ]);

        // Título
        $section->addText('HISTORIA CLINICA', ['bold' => true, 'size' => 14, 'color' => '2E74B5'], ['alignment' => 'center']);
        $section->addText('Fecha Consulta: ' . Carbon::parse($consulta->fecha)->format('d-m-Y'), ['bold' => true], ['alignment' => 'right']);

        // Título de tabla
        $section->addTextBreak(1);
        $section->addText('Datos del Paciente', ['bold' => true, 'size' => 12]);

        // Estilo de tabla y anchos definidos
        $phpWord->addTableStyle('PatientTable', [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 50,
        ]);

        $table = $section->addTable('PatientTable');

        $table->addRow();
        $table->addCell(2400)->addText('Historia Clínica:', [], 'compact');
        $table->addCell(2600)->addText($consulta->paciente->numero_historia ?? '-', [], 'compact');
        $table->addCell(2400)->addText('Nombre:', [], 'compact');
        $table->addCell(2600)->addText($consulta->paciente->nombre . ' ' . $consulta->paciente->apellido, [], 'compact');

        $table->addRow();
        $table->addCell(2400)->addText('Edad:', [], 'compact');
        $table->addCell(2600)->addText($this->edadFormateada($consulta->paciente->fecha_nacimiento), [], 'compact');
        $table->addCell(2400)->addText('Fecha Nacimiento:', [], 'compact');
        $table->addCell(2600)->addText(Carbon::parse($consulta->paciente->fecha_nacimiento)->format('d/m/Y'), [], 'compact');

        $table->addRow();
        $table->addCell(2400)->addText('C.I.:', [], 'compact');
        $table->addCell(2600)->addText($consulta->paciente->documento, [], 'compact');
        $table->addCell(2400)->addText('Comunidad:', [], 'compact');
        $table->addCell(2600)->addText($consulta->paciente->comunidad, [], 'compact');

        $section->addTextBreak(1);


        // Motivo de consulta
        $section->addText('Motivo de Consulta', ['bold' => true, 'color' => '2E74B5']);
$section->addText($consulta->motivo, [], 'compact'); // <--- Aquí se aplica

        $section->addTextBreak(1);

        // Examen físico con formato por líneas
        $section->addText('Examen Físico', ['bold' => true, 'color' => '2E74B5']);
        $lineas = explode("\n", $consulta->examen_fisico);
        foreach ($lineas as $linea) {
            $section->addText(trim($linea), [], 'compact');
        }

        // Diagnósticos
        $section->addTextBreak(1);
        $section->addText('Diagnósticos', ['bold' => true, 'color' => '2E74B5']);
        foreach ($consulta->diagnosticos as $d) {
           $section->addListItem($d->descripcion, 0, null, 'bullet', 'compact'); // <--- Aquí también
        }

        // Medicamentos
        $section->addTextBreak(1);
        $section->addText('Tratamiento', ['bold' => true, 'color' => '2E74B5']);
        foreach ($consulta->medicamentos as $m) {
           $section->addListItem("{$m->nombre} ({$m->presentacion})", 0, null, 'bullet', 'compact');
        }

        // Firma
        $section->addTextBreak(2);
        $section->addText('____________________', null, ['alignment' => 'center']);
        $section->addText('Firma y Sello', null, ['alignment' => 'center']);
        $section->addText('CENTRO DE SALUD AMBULATORIO HORNOMA', null, ['alignment' => 'center']);

        // ✅ Guardar el documento sin errores
        $fileName = 'consulta_' . Str::slug($consulta->paciente->nombre . '_' . Carbon::parse($consulta->fecha)->format('Ymd')) . '.docx';
        $filePath = storage_path("app/public/{$fileName}");

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    private function edadFormateada($fecha)
    {
        $f = \Carbon\Carbon::parse($fecha);
        $años = $f->age;
        $meses = $f->diffInMonths(now()) % 12;

        return "{$años} años {$meses} meses";
    }
}
