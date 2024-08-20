<?php

use App\Http\Controllers\ModuloController;
use Illuminate\Support\Facades\Route;

Route::get("/", [ModuloController::class, 'RefreshPay']);
