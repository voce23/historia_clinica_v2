<?php

namespace App\Livewire\Medicamentos;

use App\Models\Medicamento;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $form = [
        'id' => null,
        'nombre' => '',
        'presentacion' => '',
        'indicacion' => '',
    ];

    public $search = '';
    public $searchBy = 'nombre';
    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'form.nombre' => 'required|string|max:255',
            'form.presentacion' => 'required|string|max:255',
            'form.indicacion' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->form['id']) {
            Medicamento::find($this->form['id'])->update($this->form);
            session()->flash('message', 'Medicamento actualizado.');
        } else {
            Medicamento::create($this->form);
            session()->flash('message', 'Medicamento creado.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $this->form = Medicamento::findOrFail($id)->toArray();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        Medicamento::destroy($this->confirmingDeleteId);
        session()->flash('message', 'Medicamento eliminado.');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'nombre' => '',
            'presentacion' => '',
            'indicacion' => '',
        ];
        $this->reset(['search', 'searchBy', 'confirmingDeleteId']);
        $this->resetPage();
    }

    public function render()
    {
        $medicamentos = Medicamento::query()
            ->when($this->searchBy === 'nombre', fn($q) =>
                $q->where('nombre', 'like', "%{$this->search}%")
            )
            ->when($this->searchBy === 'presentacion', fn($q) =>
                $q->where('presentacion', 'like', "%{$this->search}%")
            )
            ->latest()->paginate(10);

        return view('livewire.medicamentos.index', [
            'medicamentos' => $medicamentos,
        ]);
    }

    public function layout(): string
    {
        return 'components.layouts.app';
    }
}
