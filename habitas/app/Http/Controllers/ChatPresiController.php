<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Chat_presi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Pusher\Pusher;


class ChatPresiController extends Controller
{

    public function index(){

        $autor_id = Auth::user()->id;

        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $historial_mensajes =  Chat_presi::where('user_id',$autor_id)->orWhere('user_id_recibido',$autor_id)->get();

        if(Auth::user()->id == $comunidad->presidente_id || Auth::user()->id == $comunidad->vicepresidente_id){
            $vecinos = DB::table('users')
            ->select('users.*')
            ->join('usuario_comunidads','usuario_comunidads.user_id','=','users.id')
            ->where('usuario_comunidads.comunidad_id',Session::get('comunidad'))
            ->where('users.id','<>',Auth::user()->id)
            ->get();
        } else {
            $vecinos = '';
        }
        return view("user.chat_presidente",compact('vecinos','comunidad','historial_mensajes'));

    }

    public function l_msg(Request $req){
        return Chat_presi::where(function ($query) use ($req) {
            $query->where('user_id',Auth::user()->id)
            ->where('user_id_recibido',$req->input('id_usu'));
        })->orWhere(function ($query) use ($req) {
            $query->where('user_id',$req->input('id_usu'))
            ->where('user_id_recibido',Auth::user()->id);
        })->where('comunidad_id',Session::get('comunidad'))->get();

        // return Chat_presi::where('user_id',Auth::user()->id)->orWhere('user_id_recibido',Auth::user()->id)->get();
    }

    public function buscar(Request $req){
        $filtro = $req->input('name');
        return DB::table('users')
        ->select('users.id','users.name','users.apellidos','users.visible')
        ->join('usuario_comunidads','usuario_comunidads.user_id','=','users.id')
        ->where('usuario_comunidads.comunidad_id',Session::get('comunidad'))
        ->where(function ($query) use ($filtro) {
            $query->where('users.name','like','%'.$filtro.'%')
            ->orWhere('users.apellidos','like','%'.$filtro.'%');
        })
        ->where('users.id','<>',Auth::user()->id)
        ->get();
    }

    public function canal_chat($channel,$event,$data){


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


    public function EnviarMensaje(Request $request){


        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));
        $comunidad_id = Session::get("comunidad");
        $autor_id = Auth::user()->id;

        try {

            $mensaje['msg'] = $request->input('mensaje');
            $mensaje['comunidad_id'] = $comunidad_id;
            if($request->has('vecino')){
                $mensaje['user_id'] = $comunidad->presidente_id;
                $mensaje['user_id_recibido'] = $request->input('vecino');
            } else {
                $mensaje['user_id'] = $autor_id;
                $mensaje['user_id_recibido'] = $comunidad->presidente_id;
            }

            Chat_presi::create($mensaje);

            $evento = $request->input('evento');//$autor_id . '_' . $comunidad_id;

            $meet_comunidad =  Comunidad::select('meet')->where('id',Session::get("comunidad"))->first()->meet;

            $data =['msg'=>$request->input('mensaje'),'id_usuario'=> Auth::user()->id,'nombre_user'=> Auth::user()->name] ;
            $this->canal_chat($meet_comunidad,$evento, $data);

            return 'OK';

        }catch(\Exception $e){
            return $e;
        }

    }


    // public function Historial_Mensajes(Request $request){

        // $comunidad = Comunidad::findOrFail(Session::get('comunidad'));
        // $autor_id = Auth::user()->id;

        // $historial_mensajes =  Chat_presi::where('id_user',$autor_id)->where('id_user_recibido',$autor_id)->get();

        // return compact('historial_mensajes');

    // }

    public function testchat($channel,$event,$data){
        $this->canal_chat($channel,$event,$data);
        return 'ok';
    }

    public function info(){
        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $info['meet'] = $comunidad->meet;
        $info['nombre'] = Auth::user()->name;
        $info['email'] = Auth::user()->email;
        $info['lang'] = Auth::user()->language;
        $info['return'] = route('dashboard');

        return $info;
    }
}
