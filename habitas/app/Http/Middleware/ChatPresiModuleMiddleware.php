<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\Contabilidad_modulo;
class ChatPresiModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Contabilidad_modulo::where('modulo_id',3)->where('activo',1)->where("comunidad_id", Session::get('comunidad'))->exists() ){
            return $next($request);
        }else{
            return redirect()->back();
        }
    }
}
