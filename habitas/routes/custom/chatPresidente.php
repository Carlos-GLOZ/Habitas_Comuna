<?php

use App\Http\Controllers\ChatPresiController;
use Illuminate\Support\Facades\Route;

// PREFIJO == /chatPresidente
Route::get("/info", [ChatPresiController::class,'info']);
Route::get("/", [ChatPresiController::class, 'index'])->name("chatPresidente");

Route::post('/buscar', [ChatPresiController::class,'buscar'])->name("chatPresidente_b");

Route::post('/list_msg', [ChatPresiController::class,'l_msg'])->name("chatPresidente_msg");

// Busqueda users, chat
// Route::get("/", [ChatPresiController::class, 'chatPresidente_view'])->name("chatPresidente");
Route::post('/EnviarMensaje', [ChatPresiController::class,'EnviarMensaje']);

// Route::post('/mensaje_recibido', [ChatPresiController::class,'mensaje_recibido']);

