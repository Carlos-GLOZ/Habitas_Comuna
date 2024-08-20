<?php

use App\Http\Controllers\UsuarioComunidadController;
use Illuminate\Support\Facades\Route;


Route::get('/lista', [UsuarioComunidadController::class, 'index'])->name('vecinos_lista');
Route::post('/echar', [UsuarioComunidadController::class, 'kick'])->name('vecinos_echar');
Route::post('/blacklist', [UsuarioComunidadController::class, 'blacklist'])->name('vecinos_blacklist');
Route::post('/unblacklist', [UsuarioComunidadController::class, 'unblacklist'])->name('vecinos_unblacklist');