<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class,'index'])->name('admin_view');
Route::post('/reboot', [AdminController::class,'reboot'])->name('reboot');
