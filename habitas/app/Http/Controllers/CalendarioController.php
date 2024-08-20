<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CalendarioController extends Controller
{
    /**
     * Devuelve la vista del calendario
     */
    public function index()
    {
        // Si el usuario no está logueado, redirigir a login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!Session::has('comunidad')) {
            return redirect()->route('menu');
        }

        // Recogemos la comunidad; los eventos y sus incidencias se recogeran asíncronamente
        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return redirect()->route('menu');
        }

        return view('comunidad.calendario', compact(['comunidad']));
    }


    /**
     * Devolver una respuesta con los eventos de una comunidad
     */
    public function eventos(Request $request)
    {
        // Si el usuario no está logueado, redirigir a login
        if (!auth()->check()) {
            return [];
        }

        if (!Session::has('comunidad')) {
            return [];
        }

        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return [];
        }

        // Si el usuario no pertenece a la comunidad, devolver array vacío
        if (!Auth::user()->can('miembro', $comunidad)) {
            return [];

        }

        return $comunidad->eventos()->with('incidencias')->get();
    }
}
