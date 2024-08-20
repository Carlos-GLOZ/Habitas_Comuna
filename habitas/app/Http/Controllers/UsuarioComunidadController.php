<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Usuario_comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UsuarioComunidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Session::has('comunidad')) {
            return redirect()->route('menu');
        }

        $comunidad = Comunidad::with('miembros', 'blacklist')->find(Session::get('comunidad'));

        if (!$comunidad) {
            return redirect()->route('menu');
        }

        // Comprobar si el usuario es miembro de la comunidad
        if (!Auth::user()->can('miembro', $comunidad)) {
            return redirect()->route('dashboard');
        }

        return view('comunidad.lista_vecinos', compact(['comunidad']));
    }

    /**
     * Echar al usuario de la comunidad
     */
    public function kick(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // esto solo puede comprobar que el usuario dado es válido y parte de almenos una comunidad
        $request->validate([
            'user_id' => 'exists:usuario_comunidads,user_id'
        ]);

        $user_id = $request->input('user_id');

        // La comunidad la hemos seteado nosotros, así que no necesitamos validarla por ahora
        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Comprobar que el usuario logueado es presidente
        if (!Auth::user()->can('presidente', $comunidad)) {
            return back();
        }

        // Comprobar que el usuario dado es parte de la comunidad
        $usuario_comunidad = $comunidad->miembros()->where(['user_id' => $user_id])->first();

        if ($usuario_comunidad == null) {
            return back();
        }

        // Eliminar al usuario de la comunidad
        $comunidad->miembros()->detach([
            'user_id' => $user_id,
        ]);

        return back();
    }

    /**
     * Bloquear al usuario de la comunidad
     */
    public function blacklist(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // esto solo puede comprobar que el usuario dado es válido
        $request->validate([
            'user_id' => 'exists:users,id'
        ]);

        $user_id = $request->input('user_id');

        // La comunidad la hemos seteado nosotros, así que no necesitamos validarla por ahora
        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Comprobar que el usuario logueado es presidente
        if (!Auth::user()->can('presidente', $comunidad)) {
            return back();
        }

        // Eliminar al usuario de la comunidad
        $comunidad->miembros()->detach([
            'user_id' => $user_id,
        ]);

        // Añadir al usuario a la blacklist
        $comunidad->blacklist()->attach([
            'user_id' => $user_id,
        ]);

        return back();
    }

    /**
     * Desbloquear al usuario de la comunidad
     */
    public function unblacklist(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // esto solo puede comprobar que el usuario dado es válido
        $request->validate([
            'user_id' => 'exists:users,id'
        ]);

        $user_id = $request->input('user_id');

        // La comunidad la hemos seteado nosotros, así que no necesitamos validarla por ahora
        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Comprobar que el usuario logueado es presidente
        if (!Auth::user()->can('presidente', $comunidad)) {
            return back();
        }

        // Comprobar que el usuario dado está blacklisteado
        $usuario_comunidad = $comunidad->blacklist()->where(['user_id' => $user_id])->first();

        if ($usuario_comunidad == null) {
            return back();
        }

        // Eliminar usuario de la blacklist
        $comunidad->blacklist()->detach([
            'user_id' => $user_id,
        ]);

        return back();
    }
}
