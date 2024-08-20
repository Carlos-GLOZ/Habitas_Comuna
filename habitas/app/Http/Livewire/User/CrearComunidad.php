<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Comunidad;
use Illuminate\Support\Facades\Http;

class CrearComunidad extends Component
{
    use WithFileUploads;

    public $name;
    public $codigo;
    public $img;

    protected $rules = [
        'name' => 'required',
        'codigo' => 'required',
    ];

    public function render()
    {
        return view('user.crear-comunidad');
    }

    // Funcion vacía para poder enviar el formulario de crear comunidad
    public function crearComunidad()
    {
        return;
    }
}
