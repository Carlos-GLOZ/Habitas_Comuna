<?php

use App\Http\Controllers\AnuncioController;
use Illuminate\Support\Facades\Route;

Route::get("/", [AnuncioController::class, 'Anuncioview'])->name("anuncio");
Route::post('/crearAnuncio', [AnuncioController::class,'crearA']);
Route::get('/manuncios', [AnuncioController::class,'anuncios']);
Route::delete('/eliminar_anuncio/{id}', [AnuncioController::class,'eliminar_anuncio']);

Route::post('/editAnuncio', [AnuncioController::class,'editA']);