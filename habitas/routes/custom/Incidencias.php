<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenciaController;


Route::get('/', [IncidenciaController::class,'view_incidencia'])->name('incidencias');
Route::get('/incidencias', [IncidenciaController::class,'get'])->name('comunidad_incidencias_async');
Route::post('/crear_incidencia', [IncidenciaController::class,'crear_incidencia']);
Route::delete('/EliminarIncidencia/{id}', [IncidenciaController::class,'eliminar_incidencia']);
Route::post('/update_incidencia', [IncidenciaController::class,'update_incidencia']);
Route::get('/ver_incidencia/{id}', [IncidenciaController::class,'ver_incidencia'])->name('incidencia.find');
Route::get('/mincidencias', [IncidenciaController::class,'mincidencias']);

Route::get('/datos_editar/{id}', [IncidenciaController::class,'datos_editar'])->name('datos_editar');
Route::post('/editar_incidencia', [IncidenciaController::class,'editar_incidencia']);

Route::post('/editar_estado', [IncidenciaController::class,'editar_estado']);
