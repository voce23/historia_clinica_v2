<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Muestra un aviso cuando se accede a un módulo aún no implementado.
 */
class AvisoEnConstruccion extends Component
{
    public function render()
    {
        return view('livewire.aviso-en-construccion');
    }
}
