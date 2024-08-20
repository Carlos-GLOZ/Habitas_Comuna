<?php

use App\Http\Controllers\ModuloController;
use Illuminate\Support\Facades\Route;

Route::get("/", [ModuloController::class, 'moduloview'])->name("modulos2");
Route::post("/payMonth", [ModuloController::class, 'payModuleFirstMonth'])->name("payModuleFirstMonth");
Route::post("/disableModule", [ModuloController::class, 'disableModuloNextMonth'])->name("disableModuloNextMonth");
Route::post("/EnableModulePayed", [ModuloController::class, 'EnableModulePayed'])->name("EnableModulePayed");
Route::post("/PayForm", [ModuloController::class, 'PayForm'])->name("PayForm");