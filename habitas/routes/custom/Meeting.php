<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::get('/',[MeetingController::class,'index'])->name('meet');
Route::get('/info',[MeetingController::class,'infoMeeting'])->name('meet_info');
