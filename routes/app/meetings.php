<?php


use App\Http\Controllers\Meetings\MeetingsController;
use Illuminate\Support\Facades\Route;



Route::resource('meetings' , MeetingsController::class);


Route::prefix('meetings')->group(function (){

    Route::post('guestLink/{meeting}' , [MeetingsController::class , 'setGuestLink'])->name('meetings.setGuestLink');

//    Route::get('guestLink/{meeting}' , [MeetingsController::class , 'getGuestLink'])->name('meetings.getGuestLink');
});
