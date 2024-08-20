<?php

use App\Http\Controllers\GastoController;
use Illuminate\Support\Facades\Route;


Route::get('/control',[GastoController::class,'index'])->name('ViewGastos');
Route::get('/',[GastoController::class,'stats'])->name('Viewstats');
Route::post('/dataset',[GastoController::class,'dataset'])->name('dataset');
