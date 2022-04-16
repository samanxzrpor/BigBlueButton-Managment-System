<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


# Show 404 Page When get Unknown Route in app
Route::fallback(function (){
    abort(404);
});

# Authentication Default Routes
Auth::routes();

# When User Just Inter Site Domain Should Redirect Auth and List
Route::get('/' , function (){ return redirect()->route('meetings.list'); });

# All Routes Should be Use Auth Middleware
Route::middleware('auth')->group(function (){

    # Meetings Process Routes
    include __DIR__ . '/my_app/meetings.php';

    # BBB Process Routes
    include __DIR__ . '/bbb/meeting.php';
});

