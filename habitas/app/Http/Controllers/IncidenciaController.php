<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Comunidad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class IncidenciaController extends Controller
{

    public function view_incidencia()
    {

        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));
        $incidencias = Incidencia::where('comunidad_id',$comunidad->id)->orderByDesc('updated_at')->orderByDesc('id')->get();

        return view("user.View_Incidencias", compact('incidencias', 'comunidad'));
    }

    public function mincidencias(){
        return Incidencia::where('comunidad_id',Session::get('comunidad'))->where('estado', 1)->orderByDesc('updated_at')->orderByDesc('id')->get();
    }

    public function crear_incidencia(Request $request)
    {
        $comunidad_id = Session::get("comunidad");
        $autor_id = Auth::user()->id;
        $estado = 1;

        try {
        $incidencia = $request->validate([
            'titulo' => 'required|max:50',
            'descripcion' => 'required|max:255',
        ]);


        $incidencia['comunidad_id'] = $comunidad_id;
        $incidencia['autor_id'] = $autor_id;
        $incidencia['estado'] = $estado;
        // dd($incidencia);
        $rest = Incidencia::create($incidencia);

        if($request->hasFile('imagen')){
            $request->validate([
                'imagen' => 'file|mimes:jpeg,png,jpg|max:2048',
            ]);

            $request->file('imagen')->storeAs('uploads/incidencia',$rest->id.'.png','public');
        }

        // if($request->hasFile('imagen')){
        //     $request->file('imagen')->storeAs('uploads/incidencia',$rest->id.'.png','public');
        // }

        return 'OK';

        }catch(\Exception $e){
            return $e;
        }

    }

    public function eliminar_incidencia($id)
    {
        // dd($request->input('id'));
        try{
            Incidencia::find($id)->delete();

            Storage::delete('public/uploads/incidencia/' . $id.'.png');

            return response()->json(['Resultado' => 'OK']);

        }catch(\Throwable $e){
            return response()->json(['Resultado' => $e]);
            // return response()->json(['Resultado' => 'Error, algo a ido mal']);
        }
        return 'OK';

    }

    public function update_incidencia(Request $request)
    {
        $id_incidencia = $request->input('id_incidencia');
        $id_estado = $request->input('id_estado');



        Incidencia::where('id',$id_incidencia)->update([
            "estado"=> $id_estado
        ]);

        return response()->json(['Resultado' => 'OK']);
        // return ['OK',$id_incidencia];
    }

    public function ver_incidencia($id)
    {

        $datos = Incidencia::find($id);

        return $datos;

    }

    public function datos_editar($id){

        $datos = Incidencia::findOrFail($id);
        return $datos;

    }

    public function editar_estado(Request $request){
        $id_incidencia = $request->input('id');
        $id_estado = $request->input('estado_select');

        Incidencia::where('id',$id_incidencia)->update([
            "estado"=> $id_estado
        ]);

        return 'OK';

    }

    public function editar_incidencia(Request $request){


        try {
        $incidencia = $request->validate([
            'titulo' => 'required|max:50',
            'descripcion' => 'required|max:255',
        ]);
        $incidencia = Incidencia::find($request->input('id'));

        $incidencia->titulo = $request->input('titulo');
        $incidencia->descripcion = $request->input('descripcion');
        $incidencia->save();



        if($request->hasFile('imagen')){
            $request->validate([
                'imagen' => 'file|mimes:jpeg,png,jpg|max:2048',
            ]);

            $request->file('imagen')->storeAs('uploads/incidencia',$incidencia->id.'.png','public');
        }

        // return 'OK';
        return ['OK',$incidencia->id];


        }catch(\Exception $e){
            return $e;
        }

    }



    public function get()
    {
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return false;
        }

        if (!Session::has('comunidad')) {
            return false;
        }

        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return false;
        }

        return $comunidad->incidencias;
    }
}
