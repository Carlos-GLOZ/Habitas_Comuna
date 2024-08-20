<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::post("insertNewCommunityToUser", [MenuController::class, 'insertNewCommunityToUser'])->name("insertNewCommunityToUser");
Route::post("getComunidadesUser", [MenuController::class, 'getComunidadesUser'])->name("getComunidadesUser");
Route::post("deleteComunidadAndUser", [MenuController::class, 'deleteComunidadAndUser'])->name("deleteComunidadAndUser");
Route::get("/", [MenuController::class, 'menu'])->name("menu");
Route::get("{id_comunidad}/establishCommunity", [MenuController::class, 'establishCommunity'])->name("establishCommunity");
Route::post("addToCommunity", [MenuController::class, 'addToCommunity'])->name("addToCommunity");
Route::get("/submenu/{menu}", [MenuController::class, 'submenu'])->name("submenu");

