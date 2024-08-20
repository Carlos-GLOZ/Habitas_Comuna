<?php

namespace App\Http\Livewire;

use App\Models\Gasto;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Gastos extends Component
{
    public $gastos, $tipo, $cantidad, $gasto, $id_gasto, $descripcion;
    public $modal = 0;
    public function render()
    {

        $this->gastos = Gasto::where('comunidad_id',Session::get('comunidad'))->get();
        return view('livewire.gastos');
    }

    public function crear(){
        $this->limpiarCampos();
        $this->abrirModal();
    }

    public function abrirModal(){
        $this->modal = true;
    }

    public function cerrarModal(){
        $this->modal = false;
    }

    public function limpiarCampos(){
        $this->tipo = 'luz';
        $this->cantidad = '';
        $this->gasto = '';
        $this->id_gasto = '';
        $this->descripcion = '';
    }

    public function editar($id){
        $gasto = Gasto::find($id);

        $this->id_gasto = $gasto->id;
        $this->gasto = $gasto->gasto;
        $this->tipo = $gasto->tipo;
        $this->cantidad = $gasto->cantidad;
        $this->descripcion = $gasto->descripcion;

        $this->abrirModal();
    }

    public function eliminar($id){
        Gasto::find($id)->delete();
    }

    public function guardar(){
        Gasto::updateOrCreate(['id' => $this->id_gasto],[
            'gasto'=>$this->gasto,
            'tipo'=>$this->tipo,
            'cantidad'=>$this->cantidad,
            'comunidad_id'=>Session::get('comunidad'),
            'descripcion'=>$this->descripcion
        ]);

        $this->cerrarModal();
        $this->limpiarCampos();
    }
}
