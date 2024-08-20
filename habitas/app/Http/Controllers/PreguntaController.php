<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Opcion;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $preguntas = Pregunta::where('encuesta_id',$id)->get();
        return view('votaciones.view_preguntas', compact('preguntas','id'));
    }

    public function listarP($id){
        return Pregunta::where('encuesta_id',$id)->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function crear(Request $req)
    {
        $id_encuesta = $req->input('id');
        $data = $req->input('encuesta');
        $data = json_decode($data);

        $pregunta = Pregunta::create([
            'encuesta_id' => $id_encuesta,
            'pregunta' => $data->pregunta
        ]);

        foreach ($data->opciones as $resp) {
            Opcion::create([
                'pregunta_id' => $pregunta->id,
                'opcion' => $resp
            ]);
        }

        return $pregunta;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pregunta $pregunta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pregunta $pregunta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function eliminar($id)
    {
        return Pregunta::findOrFail($id)->delete();
    }
}