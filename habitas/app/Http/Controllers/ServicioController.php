<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ServicioController extends Controller
{
    public function index(Request $request)
    {
        if(Session::has("comunidad")){
            $servicios = Servicio::where("comunidad_id",Session::get("comunidad"))->get();
            return view("user.servicios", compact('servicios'));
        }else{
            return back();
        }
    }

    public function getMenuAdmin(){
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                $servicios = Servicio::where("comunidad_id",Session::get("comunidad"))->get();
                return view("presidente.gestion_servicios", compact('servicios'));
            }
        }
    }

    public function insertNewService(Request $request)
    {
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["nombre"]) && !empty($request["correo"]) && !empty($request["telefono"])){
                    Servicio::create(['nombre' => $request["nombre"], 'correo' => $request["correo"], 'web' => $request["web"], 'telefono' => $request["telefono"],  'comunidad_id' => Session::get("comunidad")]);
                    return true;
                }
                //Vacio
                return back();
            }
            //No presi
            return false;
        }
        //No sesion
        return false;
    }

    public function getShowDataService(Request $request){
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                return Servicio::where("comunidad_id",Session::get("comunidad"))->get();
            }
            //No presi
            return false;
        }
        //No sesion
        return false;
    }

    public function deleteNewService(Request $request, $id)
    {
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["id"])){
                    Servicio::where("id", $id)->delete();
                    return true;
                }
                //Vacio
                return back();
            }
            //No presi
            return false;
        }
        //No sesion
        return false;
    }

    public function getDataService(Request $request, $id)
    {
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["id"])){
                    return Servicio::find($id);
                }
            }
            //No presi
            return false;
        }
        //No sesion
        return false;
    }

    public function updateDataService(Request $request)
    {
        if(Session::has("comunidad")){
            if(Auth::user()->can('presidente', Comunidad::where("id",Session::get("comunidad"))->first())){
                if(!empty($request["nombre"]) && !empty($request["correo"]) && !empty($request["telefono"])){
                    Servicio::where("id",$request["id"])->update(['nombre' => $request["nombre"], 'correo' => $request["correo"], 'web' => $request["web"], 'telefono' => $request["telefono"]]);
                    return true;
                }
                //Vacio
                return back();
            }
            //No presi
            return false;
        }
        return false;
        //No sesion
    }
}
