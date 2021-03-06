<?php


use App\Http\Controllers\Meetings\MeetingsController;
use Illuminate\Support\Facades\Route;



Route::resource('meetings' , MeetingsController::class);


Route::prefix('meetings')->group(function (){

    Route::post('guestLink/{meeting}' , [MeetingsController::class , 'setGuestLink'])->name('meetings.setGuestLink');

    Route::delete('guestLink/{meeting}' , [MeetingsController::class , 'removeGuestLink'])->name('meetings.removeGuestLink');

    Route::get('{meeting}/attendance' , [MeetingsController::class , 'getAttendance'])->name('meetings.makeAttendance');

});
