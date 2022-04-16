<?php


use App\Http\Controllers\Meetings\MeetingsController;
use Illuminate\Support\Facades\Route;



Route::resource('meetings' , MeetingsController::class);
