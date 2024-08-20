<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;


Route::get('/auth', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');

Route::get('/auth/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
