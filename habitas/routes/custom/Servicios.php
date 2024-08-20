<?php
use App\Http\Controllers\ServicioController;
use Illuminate\Support\Facades\Route;

Route::get("/", [ServicioController::class, 'index'])->name("servicios");
Route::get("/Admin", [ServicioController::class, 'getMenuAdmin'])->name("gestion_servicios");
Route::post("/insertNewService", [ServicioController::class, "insertNewService"])->name("insertNewService");
Route::delete("/deleteNewService/{id}", [ServicioController::class, "deleteNewService"])->name("deleteNewService");
Route::post("/getDataService/{id}", [ServicioController::class, "getDataService"])->name("getDataService");
Route::post("/updateDataService", [ServicioController::class, "updateDataService"])->name("updateDataService");
Route::post("/getShowDataService", [ServicioController::class, "getShowDataService"])->name("getShowDataService");
Route::post("/getShowDataService", [ServicioController::class, "getShowDataService"])->name("getShowDataService");

