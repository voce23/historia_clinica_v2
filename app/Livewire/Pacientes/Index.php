<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Componente para gestionar pacientes.
 */
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $searchBy = 'nombre';
    public $confirmingDeleteId = null;


    public $form = [
        'id' => null,
        'numero_historia' => '',
        'nombre' => '',
        'apellido' => '',
        'documento' => '',
        'fecha_nacimiento' => '',
        'comunidad' => '',
    ];

    protected function rules()
{
    $id = $this->form['id'] ?? 'NULL';

    return [
        'form.numero_historia' => 'required|string|unique:pacientes,numero_historia,' . $id,
        'form.nombre'          => 'required|string',
        'form.apellido'        => 'required|string',
        'form.documento'       => 'required|string|unique:pacientes,documento,' . $id,
        'form.fecha_nacimiento'=> 'required|date',
        'form.comunidad'       => 'nullable|string',
    ];
}


    public function render()
    {
        $pacientes = \App\Models\Paciente::query()
            ->when($this->searchBy === 'nombre', function ($query) {
                $query->whereRaw("CONCAT(nombre, ' ', apellido) LIKE ?", ['%' . $this->search . '%']);
            })
            ->when($this->searchBy === 'numero_historia', function ($query) {
                $query->where('numero_historia', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nombre')
            ->paginate(10);
        return view('livewire.pacientes.index', [
            'pacientes' => $pacientes
        ]);
        //return view('livewire.pacientes.index');

        //return view('livewire.pacientes.index', compact('pacientes'));
    }



    public function save()
{
    $this->validate();

    if ($this->form['id']) {
        // ACTUALIZAR
        \App\Models\Paciente::find($this->form['id'])->update([
            'numero_historia'    => $this->form['numero_historia'],
            'nombre'             => $this->form['nombre'],
            'apellido'           => $this->form['apellido'],
            'documento'          => $this->form['documento'],
            'fecha_nacimiento'   => $this->form['fecha_nacimiento'],
            'comunidad'          => $this->form['comunidad'],
        ]);
        session()->flash('message', 'Paciente actualizado.');
    } else {
        // CREAR
        \App\Models\Paciente::create($this->form);
        session()->flash('message', 'Paciente creado.');
    }

    $this->resetForm();
}



    public function edit($id)
{
    $paciente = \App\Models\Paciente::findOrFail($id);
    $this->form = $paciente->toArray();
}


    public function delete($id)
    {
        Paciente::destroy($id);
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        if ($this->confirmingDeleteId) {
            Paciente::destroy($this->confirmingDeleteId);
            $this->confirmingDeleteId = null;
            $this->resetForm();
            session()->flash('message', 'Paciente eliminado.');
        }
    }

    public function resetForm()
{
    $this->form = [
        'id' => null,
        'numero_historia' => '',
        'nombre' => '',
        'apellido' => '',
        'documento' => '',
        'fecha_nacimiento' => '',
        'comunidad' => '',
    ];

    $this->reset(['search', 'searchBy', 'confirmingDeleteId']);
    $this->resetPage();
}


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchBy()
    {
        $this->resetPage();
    }

    public function layout(): string
    {
        return 'components.layouts.app';
    }
}
