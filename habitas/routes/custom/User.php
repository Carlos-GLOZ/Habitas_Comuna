<?php

use App\Http\Controllers\ComunidadController;
use Illuminate\Support\Facades\Route;


Route::get('/comunidades', [ComunidadController::class,'user_vista'])->name('comunidad_vista');
Route::post('/comunidad/crearV', [ComunidadController::class,'createComu'])->name('comunidad_crear');
Route::get('/comunidad/update', [ComunidadController::class,'update_view'])->name('comunidad_editar_vista');
Route::post('/comunidad/update', [ComunidadController::class,'update'])->name('comunidad_editar');
Route::delete('/comunidad/delete', [ComunidadController::class,'delComunidad'])->name('delComunidad');
Route::post('/comunidad/abandonar', [ComunidadController::class,'abandonarComunidad'])->name('abandonarComunidad');
Route::post('/comunidad/unirse', [ComunidadController::class,'unirseComunidad'])->name('unirseComunidad');
Route::post('/comunidad/detalles', [ComunidadController::class,'find'])->name('comunidad_detalles');


Route::get('/comunidad/modulos', [ComunidadController::class,'modulos_vista'])->name('modulos_vista');
// Route::get('/comunidad/crear', function(){


//     return view('user.comunidad', compact('comunidades'));
// });
