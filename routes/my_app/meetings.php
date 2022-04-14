<?php


use App\Http\Controllers\Meetings\MeetingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('/meetings')->group(function() {

    Route::get('/list', [MeetingsController::class, 'index'])->name('meetings.list');
    Route::get('/create' , [MeetingsController::class , 'create'])->name('meetings.create');
});
