<?php

namespace App\Http\Middleware;

use App\Models\Usuario_comunidad;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class VerifyComunidad
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Usuario_comunidad::where('user_id',Auth::user()->id)->where('comunidad_id',Session::get('comunidad'))->exists()) {

            return $next($request);
        }else{
            return redirect('dashboard');
        }
    }
}
