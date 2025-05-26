<?php

namespace App\Livewire\Diagnosticos;

use App\Models\Diagnostico;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $form = [
        'id' => null,
        'codigo' => '',
        'descripcion' => '',
    ];

    public $search = '';
    public $searchBy = 'codigo';
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->form['id'] ?? 'NULL';

        return [
            'form.codigo' => 'required|string|unique:diagnosticos,codigo,' . $id,
            'form.descripcion' => 'required|string',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->form['id']) {
            Diagnostico::find($this->form['id'])->update($this->form);
            session()->flash('message', 'Diagnóstico actualizado.');
        } else {
            Diagnostico::create($this->form);
            session()->flash('message', 'Diagnóstico creado.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $this->form = Diagnostico::findOrFail($id)->toArray();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        Diagnostico::destroy($this->confirmingDeleteId);
        session()->flash('message', 'Diagnóstico eliminado.');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'codigo' => '',
            'descripcion' => '',
        ];

        $this->reset(['search', 'searchBy', 'confirmingDeleteId']);
        $this->resetPage();
    }

    public function render()
    {
        $diagnosticos = Diagnostico::query()
            ->when($this->searchBy === 'codigo', fn($q) =>
                $q->where('codigo', 'like', "%{$this->search}%")
            )
            ->when($this->searchBy === 'descripcion', fn($q) =>
                $q->where('descripcion', 'like', "%{$this->search}%")
            )
            ->orderBy('codigo')
            ->paginate(10);

        return view('livewire.diagnosticos.index', [
            'diagnosticos' => $diagnosticos,
        ]);
    }

    public function layout(): string
    {
        return 'components.layouts.app';
    }
}
