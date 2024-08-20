<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Encuesta;
use App\Models\Opcion;
use App\Models\Pregunta;
use App\Models\Presidente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PresidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orden = User::select('users.id','users.name','users.apellidos',DB::raw('COUNT(presidentes.user_id) as veces') )
        ->join('usuario_comunidads','users.id','=','usuario_comunidads.user_id')
        ->where('usuario_comunidads.comunidad_id',Session::get('comunidad'))
        ->leftJoin('presidentes', 'users.id', '=', 'presidentes.user_id')
        ->groupBy('users.id','users.name','users.apellidos')
        ->orderBy('veces', 'asc')
        ->take(2)
        ->get();
        $vecinos = Comunidad::where('id',Session::get('comunidad'))->first()->miembros;
        $comunidad = Comunidad::where('id',Session::get('comunidad'))->first();
        $presidentesH = Presidente::where('comunidad_id',Session::get('comunidad'))
        ->orderBy('fecha_ini','desc')
        ->orderBy('fecha_fin','asc')
        ->get();
        return view('presidente.presidente', compact('presidentesH','vecinos','comunidad','orden'));
    }

    public function presidente_votos(Request $request){

        $encuesta = Encuesta::create([
            'nombre' => 'Cambio de presidente '.str(Carbon::now()->format('Y-m-d')),
            'estado' => 1,
            'comunidad_id' => Session::get('comunidad')
        ]);

        $pregunta1 = Pregunta::create([
            'pregunta'=>'Proximo presidente',
            'encuesta_id' => $encuesta->id
        ]);

        $pregunta2 = Pregunta::create([
            'pregunta'=>'Proximo vicepresidente',
            'encuesta_id' => $encuesta->id
        ]);

        $vecinos = Comunidad::where('id',Session::get('comunidad'))->first()->miembros;

        foreach ($vecinos as $vecino) {

            if($vecino->id == Auth::user()->id){
                if($request->input('incluir')){
                    Opcion::create([
                        'opcion' => $vecino->name.' '.$vecino->apellidos,
                        'pregunta_id' => $pregunta1->id
                    ]);

                    Opcion::create([
                        'opcion' => $vecino->name.' '.$vecino->apellidos,
                        'pregunta_id' => $pregunta2->id
                    ]);
                }
            }else{

                Opcion::create([
                    'opcion' => $vecino->name.' '.$vecino->apellidos,
                    'pregunta_id' => $pregunta1->id
                ]);
                Opcion::create([
                    'opcion' => $vecino->name.' '.$vecino->apellidos,
                    'pregunta_id' => $pregunta2->id
                ]);
            }

        }

        return redirect()->route('dashboard');
    }

    public function presidente_cambios(Request $request){

        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        if($request->input('newPresi')){
            if($request->input('newPresi') != $comunidad->presidente_id){

                Presidente::where('comunidad_id', $comunidad->id)->where('user_id',$comunidad->presidente_id)->whereNull('fecha_fin')->update([
                    'fecha_fin' => str(Carbon::now()->format('Y-m-d'))
                ]);

                $comunidad->update([
                    'presidente_id' => $request->input('newPresi')
                ]);

                Presidente::create([
                    'user_id' => $request->input('newPresi'),
                    'comunidad_id' => Session::get('comunidad')
                ]);

            }
        }

        if($request->input('newVicePresi')){
            if($request->input('newVicePresi') == 'NULL'){
                Presidente::where('comunidad_id', $comunidad->id)->where('user_id',$comunidad->vicepresidente_id)->whereNull('fecha_fin')->update([
                    'fecha_fin' => str(Carbon::now()->format('Y-m-d'))
                ]);
                $comunidad->update([
                    'vicepresidente_id' => null
                ]);
                return back();
            }

            if($request->input('newVicePresi') != $comunidad->vicepresidente_id){

                Presidente::where('comunidad_id', $comunidad->id)->where('user_id',$comunidad->vicepresidente_id)->whereNull('fecha_fin')->update([
                    'fecha_fin' => str(Carbon::now()->format('Y-m-d'))
                ]);

                $comunidad->update([
                    'vicepresidente_id' => $request->input('newVicePresi')
                ]);


                Presidente::create([
                    'user_id' => $request->input('newVicePresi'),
                    'comunidad_id' => Session::get('comunidad')
                ]);


            }

        }






        return redirect()->back();
    }

    public function presidente_orden(){

        $usuarios = User::select('users.id','users.name','users.apellidos',DB::raw('COUNT(presidentes.user_id) as veces') )
        ->join('usuario_comunidads','users.id','=','usuario_comunidads.user_id')
        ->where('usuario_comunidads.comunidad_id',Session::get('comunidad'))
        ->leftJoin('presidentes', 'users.id', '=', 'presidentes.user_id')
        ->groupBy('users.id','users.name','users.apellidos')
        ->orderBy('veces', 'asc')

        ->get();

        return $usuarios;
    }
}
