<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Delegar;
use App\Models\Encuesta;
use App\Models\Pregunta;
use App\Models\Opcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Illuminate\Support\Facades\Redis;

class EncuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function meet_channel($channel,$event,$msg){
        $data = [
            'id'=>$msg,
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
            'cluster'=>env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
            ]
        );

        $pusher->trigger($channel,$event,$data);

        return 'OK';
    }

    public function lista_vecinos(Request $req) {
        // dd($req->except('_token'));
        return DB::table('users')
        ->select('users.id','users.name','users.visible')
        ->join('usuario_comunidads','usuario_comunidads.user_id','=','users.id')
        ->where('usuario_comunidads.comunidad_id',Session::get('comunidad'))
        ->where('users.name','like','%'.$req->input('nombre').'%')
        ->get();
    }

    public function index()
    {
        // Lista encuestas
        $encuestas = Encuesta::where('comunidad_id',Session::get('comunidad'))->orderBy('updated_at','desc')->get();
        return view('votaciones.view_encuesta', compact('encuestas'));
    }

    public function componente_encuesta_vista(){
        $encuestas = Encuesta::where('comunidad_id',Session::get('comunidad'))->orderBy('updated_at','desc')->get();
        return view('votaciones.components.encuestas', compact('encuestas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function crear(Request $req)
    {
        $req->validate([
            'nombre' => 'required'
        ]);

        //
        $encuesta = Array();
        $encuesta['nombre'] = $req->input('nombre');
        $encuesta['estado'] = 0;
        $encuesta['comunidad_id'] = Session::get('comunidad');

        $id = Encuesta::create($encuesta)->id;
        return redirect()->route('encuesta_preguntas', [$id]);
    }

    public function activarEncuesta($id){
        $encuesta = Encuesta::findOrFail($id);

        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $this->meet_channel($comunidad->meet,'encuesta',$id);

        return $encuesta->update([
            'estado' => 1
        ]);
    }

    public function viewListaVotacion(){
        $encuestas = Encuesta::where('comunidad_id', Session::get('comunidad'))
        ->where('estado',1)
        ->orWhere('estado',2)
        ->orderBy('updated_at','desc')
        ->get();
        return view('user.lista_encuestas', compact('encuestas'));
    }

    public function viewCompListaVotacion(){
        $encuestas = Encuesta::where('comunidad_id', Session::get('comunidad'))
        ->where('estado',1)
        // ->orWhere('estado',2)
        ->orderBy('updated_at','desc')
        ->get();
        return view('votaciones.components.lista_encuesta', compact('encuestas'));
    }

    public function viewVotacion($id){
        $votacion = Encuesta::findOrFail($id);
        $preguntas = $votacion->preguntas;


        return view('user.votacion', compact('preguntas','id'));
    }

    public function compviewVotacion($id){
        $votacion = Encuesta::findOrFail($id);
        $preguntas = $votacion->preguntas;
        return view('votaciones.components.votar', compact('preguntas','id'));
    }


    public function votarJunta(Request $request){
        $votacion = json_decode($request->input('votacion'));
        $user_id =  Auth::user()->id;
        $id_encuesta = $request->input('encuesta');
        $isVoted = DB::table('user_encuestas')->where('encuesta_id',$id_encuesta)->where('user_id',$user_id)->exists();

        $isDelegado = Delegar::where('emisor_id',Auth::user()->id)->where('encuesta_id',$id_encuesta)->exists();

        if($isVoted){
            return 'votado';
        }

        if($isDelegado){
            return 'delegado';
        }

        foreach ($votacion as $key => $value) {

            $pregunta = $value[0];
            $opcion = $value[1];

            $encuesta = Pregunta::findOrFail($pregunta);

            // $user->encuestas->attach($encuesta->id);

            DB::table('user_encuestas')->insert([
                'encuesta_id'=>$encuesta->id,
                'user_id'=>$user_id
            ]);

            $delegado = Delegar::where('receptor_id',Auth::user()->id)->where('encuesta_id',$encuesta->id)->get();

            $nVotos = 1 + count($delegado);

            if($opcion!='NULL'){
              Redis::incrby($pregunta.'_'.$opcion,$nVotos);
            }

        }

        return 'OK';
    }

    public function cerrarEncuesta($id){
        $encuesta = Encuesta::findOrFail($id);

        // dd($encuesta->id);

        $preguntas = Pregunta::where('encuesta_id',$encuesta->id)->get();

        //return $preguntas;
        foreach ($preguntas as $key => $pregunta) {

            $id_pregunta = $pregunta->id;

            $opciones = Opcion::where('pregunta_id',$id_pregunta)->get();
            foreach ($opciones as $key => $value) {

                $id_opcion = $value->id;

                $votos =  Redis::get($id_pregunta.'_'.$id_opcion);
                if($votos){

                    Opcion::where('id',$id_opcion)->update([
                        'votos'=> $votos,
                    ]);

                    Redis::del($id_pregunta.'_'.$id_opcion);
                }

            }
        }

        return $encuesta->update([
            'estado'=>2
        ]);
    }

    public function stats($id){
        return view('votaciones.view_estadis', compact('id'));
    }

    public function compviewStats($id){
        return view('votaciones.components.estadis', compact('id'));
    }

    public function dataStats($id){

        $votacion = Encuesta::findOrFail($id);
        $votacion = $votacion->preguntas;
        foreach ($votacion as $pregunta) {
            $pregunta->opciones;
            // aquí puedes hacer algo con las encuestas encontradas
        }
        return $votacion;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Encuesta $encuesta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Encuesta $encuesta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Encuesta $encuesta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function eliminar($id)
    {
        //
        return Encuesta::findOrFail($id)->delete();
    }
}
