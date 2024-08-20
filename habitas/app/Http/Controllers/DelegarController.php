<?php

namespace App\Http\Controllers;

use App\Models\Delegar;
use App\Models\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DelegarController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $delegar = [
            'emisor_id' => Auth::user()->id,
            'receptor_id' => $request->input('receptor_id'),
            'descripcion' => $request->input('descripcion'),
            'nombre_receptor'=>$request->input('nombre_receptor'),
            'encuesta_id'=>$request->input('encuesta_id')
        ];

        return Delegar::create($delegar);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(Encuesta::where('id',$request->input('encuesta_id'))->where('estado',0)->exists()){
            Delegar::where('encuesta_id',$request->input('encuesta_id'))->where('emisor_id',Auth::user()->id)->delete();
            return 'OK';
        }else{
            return 'NOK';
        }

    }
}
