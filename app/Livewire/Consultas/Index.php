<?php

namespace App\Livewire\Consultas;

use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\Medicamento;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $form = [
        'id' => null,
        'paciente_id' => '',
        'fecha' => '',
        'motivo' => '',
        'examen_fisico' => '',
        'diagnosticos' => [],
        'medicamentos' => [],
    ];

    public $searchPaciente = '';
    public $confirmingDeleteId = null;

    public function mount()
    {
        $this->resetForm();
    }

    protected function rules()
    {
        return [
            'form.fecha' => 'required|date',
            'form.paciente_id' => 'required|exists:pacientes,id',
            'form.motivo' => 'required|string',
            'form.examen_fisico' => 'nullable|string',
            'form.diagnosticos' => 'array',
            'form.medicamentos' => 'array',
        ];
    }

    public function save()
    {
        $this->validate();

        $consulta = Consulta::updateOrCreate(
            ['id' => $this->form['id']],
            [

                'paciente_id' => $this->form['paciente_id'],
                'fecha' => $this->form['fecha'],
                'motivo' => $this->form['motivo'],
                'examen_fisico' => $this->form['examen_fisico'],
            ]
        );

        $consulta->diagnosticos()->sync($this->form['diagnosticos']);
        $consulta->medicamentos()->sync($this->form['medicamentos']);

        session()->flash('message', 'Consulta registrada exitosamente.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $consulta = Consulta::with(['diagnosticos', 'medicamentos'])->findOrFail($id);

        $this->form = [
            'id' => $consulta->id,
            'paciente_id' => $consulta->paciente_id,
            'motivo' => $consulta->motivo,
            'examen_fisico' => $consulta->examen_fisico,
            'diagnosticos' => $consulta->diagnosticos->pluck('id')->toArray(),
            'medicamentos' => $consulta->medicamentos->pluck('id')->toArray(),
        ];
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'paciente_id' => '',
            'fecha' => now()->format('Y-m-d'),
            'motivo' => 'Paciente acude a su control.',
            'examen_fisico' => "En Regular Estado General Orientado En Las Tres Esferas.\nPiel y Mucosas: Húmedas y Normocoloreadas.\nCabeza: Normocéfalo, Sin Lesiones Aparentes.\nOjos: Pupilas Isocóricas, Normoreactivas a la Luz.\nBoca: Mucosas Húmedas, Orofaringe Normal.\nCuello: Móvil, No Adenopatías.\nTórax: Simétrico, Expansible.\nPulmones: Murmullo Vesicular Presente en Ambos Campos.\nCardiovascular: Ruidos Cardíacos Rítmicos, No Soplos.\nAbdomen: Blando, Depresible, No Doloroso.\nExtremidades: Simétricas, Sin Edemas.",
            'diagnosticos' => [],
            'medicamentos' => [],
        ];

        $this->reset(['searchPaciente']);
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.consultas.index', [
            'consultas' => Consulta::with('paciente')->latest()->paginate(10),
            'pacientes' => Paciente::where('nombre', 'like', "%{$this->searchPaciente}%")->get(),
            'diagnosticos' => Diagnostico::orderBy('codigo')->get(),
            'medicamentos' => Medicamento::orderBy('nombre')->get(),
        ]);
    }

    public function layout(): string
    {
        return 'components.layouts.app';
    }

   
    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }
    public function deleteConfirmed()
    {
        \App\Models\Consulta::destroy($this->confirmingDeleteId);
        session()->flash('message', 'Consulta eliminada.');
        $this->confirmingDeleteId = null;
    }
}
