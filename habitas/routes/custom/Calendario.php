<?php

use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;


Route::get('/', [CalendarioController::class, 'index'])->name('calendario');

Route::get('/eventos', [CalendarioController::class, 'eventos'])->name('calendario_eventos');
Route::post('/evento', [EventoController::class, 'store'])->name('calendario_evento_new');
Route::get('/evento/{id_evento}', [EventoController::class, 'find'])->name('calendario_evento');
Route::put('/eventos/{id_evento}', [EventoController::class, 'update'])->name('calendario_evento_update');
Route::delete('/eventos/{id_evento}', [EventoController::class, 'destroy'])->name('calendario_evento_destroy');