<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use App\Models\Comunidad;
use App\Models\Encuesta;
use App\Models\Evento;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/start', [AdminController::class,'start']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'Comunidad_id'
])->group(function () {
    Route::get('/dashboard', function () {

        $comunidad = Comunidad::findOrFail(Session::get('comunidad'));

        $anuncios = Anuncio::where('comunidad_id',Session::get('comunidad'))->orderBy('updated_at','desc')->take(4)->get();

        $encuestas = Encuesta::where('comunidad_id',Session::get('comunidad'))->where('estado',1)->get();

        return view('dashboard', compact('comunidad','anuncios','encuestas'));
    })->name('dashboard');
});


// Route::get('/test', [Controller::class, 'test']);

Route::get('/lang/{lang}', [Controller::class, 'lang'])->name('lang');
