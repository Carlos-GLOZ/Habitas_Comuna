<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Seguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SeguroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunidad = Comunidad::with('seguros')->find(Session::get('comunidad'));

        if (!$comunidad) {
            redirect()->route('menu');
        }

        if (!Auth::user()->can('presidente', $comunidad)) {
            redirect()->route('menu');
        }

        return view('comunidad.seguros', compact(['comunidad']));
    }

    /**
     * Devolver info de un seguro en concreto
     */
    public function find($id_seguro)
    {

        $comunidad = Comunidad::with('seguros')->find(Session::get('comunidad'));

        if (!$comunidad) {
            [];
        }

        if (!Auth::user()->can('presidente', $comunidad)) {
            [];
        }

        $seguro = Seguro::find($id_seguro);

        if (!$seguro) {
            [];
        }

        return $seguro;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'num_polizas' => 'required|max:255',
            'fecha_fin' => 'required|date|after_or_equal:today',
            'tel' => 'required',
            'correo' => 'required|email',
            'cuota' => 'required|numeric',
        ]);

        $comunidad = Comunidad::with('seguros')->find(Session::get('comunidad'));

        if (!$comunidad) {
            redirect()->route('menu');
        }

        if (!Auth::user()->can('presidente', $comunidad)) {
            redirect()->route('menu');
        }

        $datos = $request->except('_token');

        $comunidad->seguros()->create($datos);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:seguros,id',
            'nombre' => 'required|max:255',
            'num_polizas' => 'required|max:255',
            'fecha_fin' => 'required|date|after_or_equal:today',
            'tel' => 'required',
            'correo' => 'required|email',
            'cuota' => 'required|numeric',
        ]);

        $comunidad = Comunidad::with('seguros')->find(Session::get('comunidad'));

        if (!$comunidad) {
            redirect()->route('menu');
        }

        if (!Auth::user()->can('presidente', $comunidad)) {
            redirect()->route('menu');
        }

        $datos = $request->except('_token');

        $seguro = Seguro::find($datos['id']);

        $seguro->update($datos);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:seguros,id',
        ]);

        $comunidad = Comunidad::with('seguros')->find(Session::get('comunidad'));

        if (!$comunidad) {
            redirect()->route('menu');
        }

        if (!Auth::user()->can('presidente', $comunidad)) {
            redirect()->route('menu');
        }

        $datos = $request->except('_token');

        $seguro = Seguro::find($datos['id']);

        $seguro->delete();

        return back();
    }
}
