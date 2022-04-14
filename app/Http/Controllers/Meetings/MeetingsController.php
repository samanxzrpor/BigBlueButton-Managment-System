<?php

namespace App\Http\Controllers\Meetings;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use function view;

class MeetingsController extends Controller
{


    /**
     * Show the application dashboard
     */
    public function index()
    {
        $meetings = Meeting::paginate(12);

        return view('meetings.home' , compact('meetings'));
    }

    public function create()
    {
        return view('meetings.createPage');
    }
}
