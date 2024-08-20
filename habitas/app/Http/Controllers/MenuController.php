<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Modulo;
use App\Models\Contabilidad_modulo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Termwind\Components\Dd;
use TheSeer\Tokenizer\Exception;

class MenuController extends Controller
{

    // Recibir menu
    public function menu(Request $request){
        if(!Auth::check()){
            return redirect('login');
        }
        $id = Auth::user()->id;
        $comunidades = User::select("comunidads.nombre","comunidads.id")->join("usuario_comunidads","usuario_comunidads.user_id","=","users.id")->join("comunidads","comunidads.id","=","usuario_comunidads.comunidad_id")->where("users.id","=",$id)->get();
        if(count($comunidades) == 1){
            $comunidad = comunidad::findOrFail($comunidades[0]->id);
            Session::put("comunidad", $comunidad->id);

            return redirect("dashboard");
        }
        elseif(count($comunidades) >= 2){
            return view("user.menu", compact('comunidades'));
        }else{
            return view("user.menuAddCommunity");
        }

    }

    // AÑADIR A COMUNIDAD
    public function addToCommunity(Request $request){
        $codigo = $request["codigo"];
        $comunidad = Comunidad::where("codigo","=",$codigo)->first();
        if($comunidad == null){
            $error = true;
            return view("user.menuAddCommunity", compact('error'));
        }
        $idComm = $comunidad->id;
        $id = Auth::user()->id;
        $comunidadYaAsociada = comunidad::join("usuario_comunidads","usuario_comunidads.comunidad_id","=","comunidads.id")->where("usuario_comunidads.user_id",$id)->where("usuario_comunidads.comunidad_id",$idComm)->count();
        if($comunidadYaAsociada != 0){
            $error = true;
            return redirect("dashboard");
        }
        $user = User::find($id);
        Session::put("comunidad", $idComm);
        $user->comunidades()->attach($idComm);
        return redirect("dashboard");
    }


    // Recibir las comunidades de un usuario

    public function getComunidadesUser(){
        if(Session::has("comunidad")){
            try{
                $id = Auth::user()->id;
                $comunidades = Comunidad::select("comunidads.id","comunidads.nombre")->join("usuario_comunidads","comunidads.id","=","usuario_comunidads.comunidad_id")->where("usuario_comunidads.user_id",$id)->get();
                return $comunidades;
            }catch(Exception $e){
                return null;
            }
            }else{
                return null;
            }
    }


    // Establecer comunidad

    public function establishCommunity(Request $request){
        $id = $request->route("id_comunidad");
        $comunidad = Comunidad::findOrFail($id);
        Session::put("comunidad", $comunidad->id);
        return redirect("dashboard");
    }

    // Recibir submenu

    public function submenu(Request $request){

        $submenu = $request->route("menu");
        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $modulos = Contabilidad_modulo::where('activo',1)->where("comunidad_id", $comunidad->id)->pluck('modulo_id')->toArray();
        // dd(in_array('4', $modulos));
        return view("user.submenu",compact(['submenu', 'comunidad', 'modulos']));
    }


    // Insertar comunidad a un usuario desde perfil

    public function insertNewCommunityToUser(Request $request){
        if(session()->has("comunidad")){
        try{
            $codigo = $request["codigo"];
            $comunidad = Comunidad::where("codigo","=",$codigo)->first();
            if($comunidad == null){
                return false;
            }
            $idComm = $comunidad->id;
            $id = Auth::user()->id;
            $comunidadYaAsociada = comunidad::join("usuario_comunidads","usuario_comunidads.comunidad_id","=","comunidads.id")->where("usuario_comunidads.user_id",$id)->where("usuario_comunidads.comunidad_id",$idComm)->count();
            if($comunidadYaAsociada != 0){
                return redirect("dashboard");
            }
            $user = User::find($id);
            $user->comunidades()->attach($idComm);
            return true;
        }catch(Exception $e){
            return false;
        }
        }else{
            return false;
        }
    }

        // Eliminar comunidad a un usuario desde perfil

        public function deleteComunidadAndUser(Request $request){
            if(session()->has("comunidad")){
                try{
                    $comunidadSesion = session("comunidad");
                    $sesionId = $comunidadSesion->id;
                    $id = Auth::user()->id;
                    $user = User::find($id);
                    $comunidades = $request["id"];
                    $user->comunidades()->detach($comunidades);
                    if($comunidades == $sesionId){
                        session()->forget("comunidad");
                        return "stopSession";
                    }
                    return true;
                }catch(Exception $e){
                    return false;
                }
            }else{
                return false;
            }
        }




}
