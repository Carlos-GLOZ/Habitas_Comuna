<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;


// Route::get('/404', function(){
//     return view('errors.404');
// });

// Route::get('/401', function(){
//     return view('errors.401');
// });

Route::get('/500', function(){
    return view('errors.500');
});

Route::get('/offline', function(){
    return view('errors.offline');
});

Route::get('/check', function(){
    return view('auth.checkEmail');
});
