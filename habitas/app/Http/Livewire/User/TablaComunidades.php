<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class TablaComunidades extends Component
{
    public $comunidades;

    public function render()
    {
        return view('user.tabla-comunidades');
    }
}
