<?php

namespace App\Livewire\Establecimientos;

use App\Models\Establecimiento;
use Livewire\Component;

/**
 * Gestión de Establecimientos (CSA / Hospitales)
 */
class Index extends Component
{
    public $establecimientos, $nombre, $codigo, $tipo, $municipio, $activo = true;
    public $modoEditar = false, $establecimientoId;

    public function render()
    {
        $this->establecimientos = Establecimiento::orderBy('nombre')->get();
        return view('livewire.establecimientos.index');
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:establecimientos,codigo,' . $this->establecimientoId,
            'tipo' => 'required|in:CSA,Hospital',
            'municipio' => 'nullable|string|max:255',
        ]);

        Establecimiento::updateOrCreate(
            ['id' => $this->establecimientoId],
            [
                'nombre' => $this->nombre,
                'codigo' => $this->codigo,
                'tipo' => $this->tipo,
                'municipio' => $this->municipio,
                'activo' => $this->activo,
            ]
        );

        session()->flash('message', $this->modoEditar ? 'Establecimiento actualizado' : 'Establecimiento creado');
        $this->resetFormulario();
    }

    public function editar($id)
    {
        $this->modoEditar = true;
        $item = Establecimiento::findOrFail($id);
        $this->establecimientoId = $item->id;
        $this->nombre = $item->nombre;
        $this->codigo = $item->codigo;
        $this->tipo = $item->tipo;
        $this->municipio = $item->municipio;
        $this->activo = $item->activo;
    }

    public function eliminar($id)
    {
        Establecimiento::destroy($id);
        session()->flash('message', 'Establecimiento eliminado');
        $this->resetFormulario();
    }

    public function resetFormulario()
    {
        $this->reset(['nombre', 'codigo', 'tipo', 'municipio', 'activo', 'modoEditar', 'establecimientoId']);
    }
}
