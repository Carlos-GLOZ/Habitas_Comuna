<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Modulo;
use App\Models\User;
use App\Models\Usuario_comunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class ComunidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('user.comunidad');
    }

    public function modulos_vista(){
        return view('user.modulos');
    }

    public function listComunidad(){
        $comunidades = Comunidad::all();

        return $comunidades;
    }

    public function createComu(Request $req)
    {
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        /**
         * Renombrar los inputs en caso necesário
         */

        // Creamos una tabla de nombres
        $tabla_renombres = [
            'crear_nombre' => 'nombre',
            'crear_codigo' => 'codigo',
            'crear_correo' => 'correo'
        ];

        // Validamos los inputs teniendo en cuenta también estos nuevos nombres
        $req->validate([
            'nombre' => 'required_without:crear_nombre',
            'codigo' => 'required_without:crear_codigo|unique:comunidads,codigo',
            'correo' => 'email',
            'crear_nombre' => 'required_without:nombre',
            'crear_codigo' => 'required_without:codigo|unique:comunidads,codigo'
        ],
            [
                'nombre' => __('The name field is required'),
                'codigo' => __('The code field is required'),
                'crear_nombre' => __('The name field is required'),
                'crear_codigo' => __('The code field is required')
            ]
        );

        // Hacemos una tabla de parametros nueva
        $new_params = [
            'nombre' => null,
            'codigo' => null,
            'correo' => null,
            'img' => null,
        ];

        // Rellenar parametros con nombres distintos
        foreach ($tabla_renombres as $key => $value) {
            if (isset($req->except('_token')[$key])) { // Si la request tiene una key que está presente en la tabla de nombres
                $new_params[$value] = $req->except('_token')[$key];
            }
        }

        // Rellenar el resto de valores
        foreach ($new_params as $key => $value) {
            if ($value == null && isset($req->except('_token')[$key])) { // Si el valor todavía es null y la request tiene un valor con esa key
                $new_params[$key] = $req->except('_token')[$key];
            }
        }

        // Reemplazar tabla de request
        $req->request->replace($new_params);

        $comunidad = Comunidad::create([
            //'creador_id'=>Auth::user()->id,
            'presidente_id'=>Auth::user()->id,
            'nombre' => $req->input('nombre'),
            'codigo' => $req->input('codigo'),
            'correo' => $req->input('correo'),
            'meet' => Uuid::uuid4()->toString()
        ]);

        $comunidad->miembros()->attach([
            'user_id' => Auth::user()->id,
        ]);

        // Modulo::create([
        //     'comunidad_id' => $comunidad->id
        // ]);

        if($req->file('img')){
            $req->file('img')->storeAs('uploads/comunidades/img',$comunidad->id.'.png','public');
        }


        Session::put("comunidad", $comunidad->id);

        return redirect()->back();
    }

    public function delComunidad(Request $req){
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $comunidad = Comunidad::find($req->input('id'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return redirect()->route('menu');
        }

        // Si el usuario no pertenece a la comunidad, devolver atrás
        if (!Auth::user()->can('miembro', $comunidad)) {
            return back()->withErrors([
                'accion' => __("You don't have permissions to do this"),
            ]);
        }

        // Si el usuario no es el presidente de la comunidad, devolver atrás
        if (auth()->user()->cannot('presidente', $comunidad)) {
            return back()->withErrors([
                'accion' => __("You don't have permissions to do this"),
            ]);
        }

        $comunidad->delete();

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $comunidad = Comunidad::find($req->input('id'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return redirect()->route('menu');
        }

        // Si el usuario no pertenece a la comunidad, devolver atrás
        if (!Auth::user()->can('miembro', $comunidad)) {
            return back()->withErrors([
                'accion' => __("You don't have permissions to do this"),
            ]);
        }

        // Si el usuario no es el presidente de la comunidad, devolver atrás
        if (auth()->user()->cannot('presidente', $comunidad)) {
            return back()->withErrors([
                'accion' => __("You don't have permissions to do this"),
            ]);
        }

        $req->validate([
            'nombre' => 'required',
            'codigo' => 'required',
            'presidente_id' => 'required|exists:users,id'
        ]);

        // Comprobar que el codigo dado no existe, en caso de que lo estén intentando cambiar
        if ($req->input('codigo') != $comunidad->codigo) {
            $comunidades_con_codigo = Comunidad::where(['codigo' => $req->input('codigo')])->count();

            if ($comunidades_con_codigo > 0) {
                return back()->withErrors([
                    'codigo' => __('The code is already being used by another community'),
                ]);
            }
        }

        // Comprobar que el presidente dado es miembro de la comunidad
        $presidente_dado = User::find($req->input('presidente_id'));

        if ($presidente_dado->cannot('miembro', $comunidad)) {
            return back()->withErrors([
                'presidente_id' => __("The user selected is not part of this community"),
            ]);
        }

        // Comprobar si no han puesto vicepresidente
        $vicepresidente = $req->input('vicepresidente_id');


        if ($req->input('vicepresidente_id') == 'null') {
            $vicepresidente = null;
        } else {
            $vicepresidente = $req->input('vicepresidente_id');

            $vicepresidente_dado = User::find($req->input('vicepresidente_id'));

            if ($vicepresidente_dado->cannot('miembro', $comunidad)) {
                return back()->withErrors([
                    'vicepresidente_id' => __("The user selected is not part of this community"),
                ]);
            }
        }


        $comunidad->update([
            'nombre' => $req->input('nombre'),
            'codigo' => $req->input('codigo'),
            'presidente_id' => $req->input('presidente_id'),
            'vicepresidente_id' => $vicepresidente
        ]);

        // Comprobar que el codigo dado no existe, en caso de que lo estén intentando cambiar
        if ($req->input('codigo') != $comunidad->codigo) {
            $comunidades_con_codigo = Comunidad::where(['codigo' => $req->input('codigo')])->count();

            if ($comunidades_con_codigo > 0) {
                return back()->withErrors([
                    'codigo' => __('The code is already being used by another community'),
                ]);
            }
        }

        // Comprobar que el presidente dado es miembro de la comunidad
        $presidente_dado = User::find($req->input('presidente_id'));

        if ($presidente_dado->cannot('miembro', $comunidad)) {
            return back()->withErrors([
                'presidente_id' => __("The user selected is not part of this community"),
            ]);
        }

        // Comprobar si no han puesto vicepresidente
        $vicepresidente = $req->input('vicepresidente_id');


        if ($req->input('vicepresidente_id') == 'null') {
            $vicepresidente = null;
        } else {
            $vicepresidente = $req->input('vicepresidente_id');

            $vicepresidente_dado = User::find($req->input('vicepresidente_id'));

            if ($vicepresidente_dado->cannot('miembro', $comunidad)) {
                return back()->withErrors([
                    'vicepresidente_id' => __("The user selected is not part of this community"),
                ]);
            }
        }


        $comunidad->update([
            'nombre' => $req->input('nombre'),
            'codigo' => $req->input('codigo'),
            'presidente_id' => $req->input('presidente_id'),
            'vicepresidente_id' => $vicepresidente
        ]);

        // Comprobar que el codigo dado no existe, en caso de que lo estén intentando cambiar
        if ($req->input('codigo') != $comunidad->codigo) {
            $comunidades_con_codigo = Comunidad::where(['codigo' => $req->input('codigo')])->count();

            if ($comunidades_con_codigo > 0) {
                return back()->withErrors([
                    'codigo' => __('The code is already being used by another community'),
                ]);
            }
        }

        // Comprobar que el presidente dado es miembro de la comunidad
        $presidente_dado = User::find($req->input('presidente_id'));

        if ($presidente_dado->cannot('miembro', $comunidad)) {
            return back()->withErrors([
                'presidente_id' => __("The user selected is not part of this community"),
            ]);
        }

        // Comprobar si no han puesto vicepresidente
        $vicepresidente = $req->input('vicepresidente_id');


        if ($req->input('vicepresidente_id') == 'null') {
            $vicepresidente = null;
        } else {
            $vicepresidente = $req->input('vicepresidente_id');

            $vicepresidente_dado = User::find($req->input('vicepresidente_id'));

            if ($vicepresidente_dado->cannot('miembro', $comunidad)) {
                return back()->withErrors([
                    'vicepresidente_id' => __("The user selected is not part of this community"),
                ]);
            }
        }


        $comunidad->update([
            'nombre' => $req->input('nombre'),
            'codigo' => $req->input('codigo'),
            'correo' => $req->input('correo'),
            'presidente_id' => $req->input('presidente_id'),
            'vicepresidente_id' => $vicepresidente
        ]);

        if($req->file('img')){
            $req->file('img')->storeAs('uploads/comunidades/img',$req->input('id').'.png','public');
        }

        return back();
    }

    public function update_view(Request $req)
    {
        $comunidad = Comunidad::where('id',$req->input('id'))->firstOrFail();

        $vecinos = User::join('usuario_comunidads','usuario_comunidads.user_id','users.id' )
        ->where('usuario_comunidads.comunidad_id',$comunidad->id)->get();

        return view('user.comunidad_edit', compact('comunidad','vecinos'));
    }


    public function user_vista(){
        $comunidades = Auth::user()->comunidades()->with(['presidente', 'vicepresidente'])->get();
        return view('user.comunidad', compact('comunidades'));
    }

    /**
     * Abandonar comunidad
     */
    public function abandonarComunidad(Request $request)
    {
        $comunidad = Comunidad::find($request->input('id'));

        if (!$comunidad) {
            return back();
        }

        if ($comunidad->miembros()->find(Auth::user()->id)) {
            $comunidad->miembros()->detach(Auth::user()->id);
        }

        // Si la comunidad abandonada es en la que está el usuario en la sesión, quitarla de ahí
        if (Session::has('comunidad') && Session::get('comunidad') == $comunidad->id) {
            Session::remove('comunidad');
        }

        return back();
    }

    /**
     * Unirse a una comunidad mediante su código
     */
    public function unirseComunidad(Request $request)
    {
        /**
         * Renombrar los inputs en caso necesário
         */

        // Creamos una tabla de nombres
        $tabla_renombres = [
            'unirse_codigo' => 'codigo',
        ];

        // Validamos los inputs teniendo en cuenta también estos nuevos nombres
        $request->validate([
            'codigo' => 'required_without:unirse_codigo|exists:comunidads,codigo',
            'unirse_codigo' => 'required_without:codigo|exists:comunidads,codigo'
        ],
            [
                'codigo' => __('The code field is required'),
                'unirse_codigo' => __('The code field is required')
            ]
        );

        // Hacemos una tabla de parametros nueva
        $new_params = [
            'codigo' => null,
        ];

        // Rellenar parametros con nombres distintos
        foreach ($tabla_renombres as $key => $value) {
            if (isset($request->except('_token')[$key])) { // Si la request tiene una key que está presente en la tabla de nombres
                $new_params[$value] = $request->except('_token')[$key];
            }
        }

        // Rellenar el resto de valores
        foreach ($new_params as $key => $value) {
            if ($value == null && isset($request->except('_token')[$key])) { // Si el valor todavía es null y la request tiene un valor con esa key
                $new_params[$key] = $request->except('_token')[$key];
            }
        }

        // Reemplazar tabla de request
        $request->request->replace($new_params);

        $comunidad = Comunidad::where(['codigo' => $request->input('codigo')])->first();

        // Si la comunidad no existe
        if (!$comunidad) {
            return back();
        }

        if(Usuario_comunidad::where('user_id',Auth::user()->id)->where('comunidad_id',$comunidad->id)->exists() ){
            return back();
        }

        // Si el usuario está blacklisteado por la comunidad
        if (!Auth::user()->can('unirse', $comunidad)) {
            return back()->withErrors([
                'blacklist' => __('You are blacklisted from this community'),
            ]);
        }

        // Si todas las validaciones son correctas, unir al usuario a la comunidad
        $comunidad->miembros()->attach([
            'user_id' => Auth::user()->id,
        ]);

        return back();
    }

    /**
     * Devolver los detalles de una comunidad
     */
    public function find(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:comunidads,id'
        ]);

        $comunidad = Comunidad::with('miembros', 'presidente', 'vicepresidente')->find($request->input('id'));

        // Si el usuario no es miembro de la comunidad, devolver array vacío
        if (!Auth::user()->can('miembro', $comunidad)) {
            return [];
        }

        // Si el usuario es presidente de la comunidad, devolver array con info completa
        if (Auth::user()->can('presidente', $comunidad)) {
            $comunidad->isUserPresidente = true;

            return $comunidad;
        } else { // Sino, el usuario será un miembro normal de la comunidad
            $comunidad->isUserPresidente = false;

            // Devolver comunidad con atributos ofuscados
            return $comunidad->ofuscar();
        }
    }
}
