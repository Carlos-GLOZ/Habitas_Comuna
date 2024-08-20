<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Contabilidad_modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Pusher\Pusher;

class MeetingController extends Controller
{
    public function index(){
        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $modulos = Contabilidad_modulo::where('activo',1)->where("comunidad_id", $comunidad->id)->pluck('modulo_id')->toArray();

        return view('user.meeting',compact('comunidad','modulos'));
    }

    public function infoMeeting(){
        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $info['meet'] = $comunidad->meet;
        $info['nombre'] = Auth::user()->name;
        $info['email'] = Auth::user()->email;
        $info['lang'] = Auth::user()->language;
        $info['return'] = route('dashboard');

        return $info;
    }

    public function meet_channel($channel,$event,$msg){
        $data = [
            'msg'=>$msg,
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
}
