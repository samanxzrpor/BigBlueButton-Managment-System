<?php

use App\Http\Controllers\BBB\BBBController;
use Illuminate\Support\Facades\Route;



Route::prefix('bbb')->group(function (){

    Route::post('create/{meeting}' , [BBBController::class , 'create'])->name('bbb.create');

    Route::get('end/{meeting}' , [BBBController::class , 'end'])->name('bbb.end');

    Route::get('list' , [BBBController::class , 'list'])->name('bbb.list');

    Route::post('join/{meeting}' , [BBBController::class , 'join'])->name('bbb.join');

    Route::get('participants/{meeting}' , [BBBController::class , 'attendanceLog'])->name('bbb.participants');
});
