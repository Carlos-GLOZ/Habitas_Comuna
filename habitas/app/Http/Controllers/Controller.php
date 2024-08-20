<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use App\Models\Comunidad;
use App\Models\Encuesta;
use App\Models\Evento;
use App\Models\Incidencia;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function test()
    {
        if (!Session::has('comunidad')) {
            Session::put('comunidad', 1);
        }

        dd(Comunidad::with(['incidencias.eventos', 'eventos.incidencias'])->find(Session::get('comunidad')));
    }

    /**
     * Devolver el json del lenguaje especificado
     */
    public function lang($lang)
    {
        // dd(file_get_contents(resource_path('../lang/'.$lang.'.json')));

        return file_get_contents(resource_path('../lang/'.$lang.'.json'));
    }
}
