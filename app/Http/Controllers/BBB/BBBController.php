<?php

namespace App\Http\Controllers\BBB;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Services\BBBService;
use Illuminate\Http\Request;

class BBBController extends Controller
{

    public function create(Meeting $meeting)
    {
        # Create Meeting in BBB Server
        resolve(BBBService::class)->createSession($meeting);

        # change Meeting Status in our Database
        $meeting->update([
            'status' => 'Performing'
        ]);

        return back()->with('success' , 'You Can Join Meeting');
    }


    public function list()
    {
        $meetings = resolve(BBBService::class)->getMeetings();
    }


    public function join(Meeting $meeting)
    {
        $meetingData = unserialize($meeting->meeting_data);
        $url = resolve(BBBService::class)->joinMeeting(
            $meetingData['meetingId'] ,
            $meeting->title,
            $meetingData['attendeePassword']);
        return redirect($url);
    }


    public function end(Meeting $meeting)
    {
        $meetingData = unserialize($meeting->meeting_data);
        $response = resolve(BBBService::class)->endMeeting( $meetingData['meetingId'] ,
            $meeting->title,
            $meetingData['moderatorPassword']);

        $meeting->update([
            'status' => 'Closed'
        ]);
    }
}
