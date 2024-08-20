<?php

namespace App\Http\Livewire;

use App\Models\Gasto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class StatsGastos extends Component
{
    public $gastos;
    public $year;
    public $tipo = 'all';
    public function render()
    {

        if($this->year == null){
            $this->year = date('Y');
        }

        if($this->tipo == 'all'){
            $this->gastos= Gasto::where('comunidad_id',Session::get('comunidad'))->whereYear('created_at', '=', $this->year)->get();
        }else{
            $this->gastos= Gasto::where('comunidad_id',Session::get('comunidad'))->whereYear('created_at', '=', $this->year)->where('tipo',$this->tipo)->get();
        }

        return view('livewire.stats-gastos');

    }



}
