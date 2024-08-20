<?php

namespace App\Http\Middleware;

use App\Models\Contabilidad_modulo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class MeetModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(Contabilidad_modulo::where('modulo_id',1)->where('activo',1)->where("comunidad_id", Session::get('comunidad'))->exists() ){
            return $next($request);
        }else{
            return redirect()->back();
        }

    }
}
