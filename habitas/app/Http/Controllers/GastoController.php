<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gastos.gastos');
    }

    public function stats()
    {

        return view('gastos.stats');
    }

    public function dataset(Request $request){
        $tipo = $request->input('tipo');
        $year = $request->input('año');

        $consumo = Gasto::where('comunidad_id',Session::get('comunidad'))->where('tipo',$tipo)->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(cantidad) as total'))
        ->whereYear('created_at', '=', $year)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get()
        ->pluck('total','month')->toArray();

        if (count($consumo)==1) {
            $consumo = array($consumo);
        }


        $gasto= Gasto::where('comunidad_id',Session::get('comunidad'))->where('tipo',$tipo)->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(gasto) as total'))
        ->whereYear('created_at', '=', $year)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get()
        ->pluck('total','month')->toArray();

        if (count($gasto)==1) {
            $gasto = array($gasto);
        }

        $todo= Gasto::where('comunidad_id',Session::get('comunidad'))->select(DB::raw('tipo'), DB::raw('SUM(gasto) as total'))
        ->whereYear('created_at', '=', $year)
        ->groupBy('tipo')
        ->get()
        ->pluck('total','tipo')->toArray();

        // if (count($todo)==1) {
        //     $todo = array($todo);
        // }

        return [$gasto, $consumo,$todo];
    }
}
