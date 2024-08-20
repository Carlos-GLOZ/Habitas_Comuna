<?php

use App\Http\Controllers\PresidenteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PresidenteController::class,'index'])->name('presidente_elec');
Route::post('/votos', [PresidenteController::class,'presidente_votos'])->name('presidente_votos');
Route::get('/orden', [PresidenteController::class,'presidente_orden'])->name('presidente_orden');

Route::post('/cambios/presi', [PresidenteController::class,'presidente_cambios'])->name('presidente_cambios');
