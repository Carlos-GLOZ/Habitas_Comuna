<?php

use App\Http\Controllers\DelegarController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\PreguntaController;
use Illuminate\Support\Facades\Route;

// Encuesta == Junta
Route::get('/encuestas', [EncuestaController::class,'index'])->name('encuesta_vista');
Route::post('/encuestasC', [EncuestaController::class,'crear'])->name('encuesta_crear');
Route::delete('/encuesta/{id}', [EncuestaController::class,'eliminar'])->name('encuesta_elimina');

// Vista usuario [Votar]
Route::get('/lista_encuestas', [EncuestaController::class,'viewListaVotacion'])->name('encuesta_lista');
Route::get('/votar/{id}',[EncuestaController::class, 'viewVotacion'])->name('encuesta_responder');
Route::post('/votar_encuesta',[EncuestaController::class, 'votarJunta'])->name('encuesta_responder');

// Estados de una encuesta
Route::put('/activarEncuesta/{id}', [EncuestaController::class,'activarEncuesta'])->name('encuesta_activa');
Route::put('/cerrarEncuesta/{id}', [EncuestaController::class,'cerrarEncuesta'])->name('encuesta_cerrar');
Route::get('/stats/{id}',[EncuestaController::class, 'stats']);

// Datos encuesta
Route::get('/dataset/{id}',[EncuestaController::class, 'dataStats']);

// Preguntas
Route::get('/encuestas/{id}', [PreguntaController::class,'index'])->name('encuesta_preguntas');
Route::get('/lista_preguntas/{id}', [PreguntaController::class,'listarP'])->name('preguntas');
Route::post('/new_pregunta', [PreguntaController::class,'crear'])->name('crear_preguntas');

Route::delete('/del_pregunta/{id}', [PreguntaController::class, 'eliminar'])->name('eliminar_pregunta');

//Delegar
Route::post('/delegar', [DelegarController::class,'create'])->name('delegar');
// Route::delete('/', [ComunidadController::class,'delComunidad'])->name('delComunidad');
// Route::get('/comunidad/modulos', [ComunidadController::class,'modulos_vista'])->name('modulos_vista');

Route::post('/lista_vec', [EncuestaController::class,'lista_vecinos'])->name('lista_vecinos');


//View Components
Route::get('/comp/encuestas', [EncuestaController::class,'componente_encuesta_vista'])->name('componente_encuesta_vista');
Route::get('/comp/votar/{id}',[EncuestaController::class, 'compviewVotacion'])->name('componente_votacion_vista');
Route::get('/comp/stats/{id}',[EncuestaController::class, 'compviewStats'])->name('componente_stats_vista');
Route::get('/comp/lista_encuestas', [EncuestaController::class,'viewCompListaVotacion'])->name('viewCompListaVotacion');
