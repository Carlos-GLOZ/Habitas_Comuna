<?php

use App\Http\Controllers\SeguroController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SeguroController::class, 'index'])->name('seguros');
Route::post('/create', [SeguroController::class, 'create'])->name('seguros.create');
Route::post('/update', [SeguroController::class, 'update'])->name('seguros.update');
Route::get('/find/{id_seguro}', [SeguroController::class, 'find'])->name('seguros.find');
Route::post('/delete', [SeguroController::class, 'destroy'])->name('seguros.delete');