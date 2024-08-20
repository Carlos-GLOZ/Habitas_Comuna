<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Comunidad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AnuncioController extends Controller
{

    public function Anuncioview()
    {

        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));
        $anuncios = Anuncio::where('comunidad_id',$comunidad->id)->orderByDesc('id')->get();

        return view("user.View_anuncio", compact('anuncios', 'comunidad'));
    }

    public function anuncios(){
        return Anuncio::where('comunidad_id',Session::get('comunidad'))->orderByDesc('id')->get();
    }


    public function crearA(Request $req) {

        try {
            $anuncio = $req->validate([
                'Titulo' => 'required|max:50',
                'Informacion' => 'required|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $errores = $exception->validator->getMessageBag()->toArray();
            return $errores;
        }

        $anuncio['comunidad_id'] = Session::get('comunidad');
        $anuncio['nombre'] = $req->input('Titulo');
        $anuncio['descripcion'] = $req->input('Informacion');

        $rest = Anuncio::create($anuncio);

        if($req->hasFile('imagen')){
            $req->validate([
                'imagen' => 'file|mimes:jpeg,png,jpg|max:2048',
            ]);
            $req->file('imagen')->storeAs('uploads/anuncio',$rest->id.'.png','public');
        }

        return ['OK',$rest,$req->hasFile('imagen')];
    }

    public function editA(Request $req) {
        try {
            $anuncio = $req->validate([
                'Titulo' => 'required|max:50',
                'Informacion' => 'required|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $errores = $exception->validator->getMessageBag()->toArray();
            return $errores;
        }

        $update = array();
        $update['nombre'] = $req->Titulo;
        $update['descripcion'] = $req->Informacion;

        $rest = Anuncio::find($req->anuncio);
        $rest->update($update);
        // dd($rest->id);


        if($req->hasFile('imagen')){
            $req->validate([
                'imagen' => 'file|mimes:jpeg,png,jpg|max:2048',
            ]);
            $req->file('imagen')->storeAs('uploads/anuncio',$rest->id.'.png','public');
        }

        return ['OK',$rest->id];
    }


    public function eliminar_anuncio($id)
    {
        // dd($id);
        try{
            Anuncio::find($id)->delete();

            Storage::delete('public/uploads/anuncio/' . $id.'.png');

            return response()->json(['Resultado' => 'OK']);

        }catch(\Throwable $e){
            return response()->json(['Resultado' => $e]);
            // return response()->json(['Resultado' => 'Error, algo a ido mal']);
        }
        return 'OK';

    }


}
