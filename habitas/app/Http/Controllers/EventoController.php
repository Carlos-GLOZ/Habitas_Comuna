<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EventoController extends Controller
{
    /**
     * Devolver la info de un evento
     */
    public function find(Request $request, $id_evento)
    {
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return false;
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

        $evento = Evento::with('incidencias')->find($id_evento);

        return $evento;
    }

    /**
     * Crear un nuevo evento
     */
    public function store(Request $request)
    {
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return back();
        }

        if (!Session::has('comunidad')) {
            return redirect()->route('menu');
        }

        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return redirect()->route('menu');
        }

        // Si el usuario no pertenece a la comunidad, devolver atrás
        if (!Auth::user()->can('miembro', $comunidad)) {
            return back();

        }

        // Si el usuario no es el presidente de la comunidad
        if (auth()->user()->cannot('presidente', $comunidad)) {
            return back();
        }

        // Validar formulario
        $request->validate([
            'nombre' => 'required',
            'fecha_ini' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'descripcion' => 'required'
        ]);

        $evento = $request->except('_token', 'incidencias');

        // Tipo estático porque no lo hemos llegado a poner
        $evento['tipo'] = '';

        // Añadir evento - el metodo sync() crea registros en la tabla intermedia entre evento e incidencias con las IDs dadas y elimina registros cuyas IDs no estén en el array dado
        $comunidad->eventos()->create($evento)->incidencias()->sync($request->input('incidencias'));

        // Siempre devolvemos back() independientemente de si se ha creado el registro exitosamente
        return back();
    }

    /**
     * Eliminar un evento
     */
    public function destroy(Request $request, $id_evento)
    {
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return back();
        }

        if (!Session::has('comunidad')) {
            return redirect()->route('menu');
        }

        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return redirect()->route('menu');
        }

        // Si el usuario no pertenece a la comunidad, devolver atrás
        if (!Auth::user()->can('miembro', $comunidad)) {
            return back();
        }

        // Si el usuario no es el presidente de la comunidad
        if (auth()->user()->cannot('presidente', $comunidad)) {
            return back();
        }

        $evento = Evento::find($id_evento);

        // Si no encuentra la comunidad, devolver false
        if (!$evento) {
            return back();
        }

        // Sincronizar con un array vacío para eliminar registros de la tabla intermedia
        $evento->incidencias()->sync([]);
        // Eliminar evento
        $evento->delete();

        // Siempre devolvemos back() independientemente de si se ha creado el registro exitosamente
        return back();
    }

    /**
     * Actualizar un evento
     */
    public function update(Request $request, $id_evento)
    {
        // Si el usuario no está logueado, devolver false
        if (!auth()->check()) {
            return back();
        }

        if (!Session::has('comunidad')) {
            return redirect()->route('menu');
        }

        $comunidad = Comunidad::find(Session::get('comunidad'));

        // Si no encuentra la comunidad, redirigir a menu
        if (!$comunidad) {
            return redirect()->route('menu');
        }

        // Si el usuario no pertenece a la comunidad, devolver atrás
        if (!Auth::user()->can('miembro', $comunidad)) {
            return back();

        }

        // Si el usuario no es el presidente de la comunidad
        if (auth()->user()->cannot('presidente', $comunidad)) {
            return back();
        }

        $evento = Evento::find($id_evento);

        // Si no encuentra la comunidad, devolver false
        if (!$evento) {
            return back();
        }

        // Actualizar modelo
        $evento->update($request->except('_token', '_method', 'incidencias'));

        // Sincronizar con un array vacío para eliminar registros de la tabla intermedia
        $evento->incidencias()->sync($request->input('incidencias'));


        // Siempre devolvemos back() independientemente de si se ha creado el registro exitosamente
        return back();
    }
}
