<?php

use App\Http\Controllers\ArchivoController;
use Illuminate\Support\Facades\Route;

Route::get("/Admin", [ArchivoController::class, 'getMenuAdmin'])->name("gestion_adjuntos");
Route::get("/", [ArchivoController::class, 'index'])->name("adjuntos");
Route::post("/addNewAdjunto", [ArchivoController::class, 'addNewAdjunto'])->name("addNewAdjunto");
Route::post("/deleteAdjunto", [ArchivoController::class, 'deleteAdjunto'])->name("deleteAdjunto");
Route::post("/getShowDataAdjunto", [ArchivoController::class, "getShowDataAdjunto"])->name("getShowDataAdjunto");
Route::post("/getDataAdjunto/{id}", [ArchivoController::class, "getDataAdjunto"])->name("getDataAdjunto");
Route::post("/updateDataAdjunto", [ArchivoController::class, 'updateDataAdjunto'])->name("updateDataAdjunto");
Route::get("/descargarArchivoPrivado/{archivo}", [ArchivoController::class, 'descargarArchivoPrivado'])->name("descargarArchivoPrivado");
Route::get("/seeArchiveInNavigator/{archivo}/{tipo}", [ArchivoController::class, 'seeArchiveInNavigator'])->name("seeArchiveInNavigator");
