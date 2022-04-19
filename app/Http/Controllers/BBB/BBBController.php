<?php

namespace App\Http\Controllers\BBB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Meetings\AttendenceController;
use App\Models\Meeting;
use App\Services\BBBService;
use BigBlueButton\Exceptions\BadResponseException;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class BBBController extends Controller
{

    private BBBService $bbb;

    # Initialize BBB Service
    public function __construct(BBBService $bbb)
    {
        $this->bbb = $bbb;
    }


    public function create(Meeting $meeting)
    {
        try {
            # Throw Exception If it is past the start time of the meeting
            if ($meeting->start_meeting_time < now())
                throw new Exception('it is past the start time of the meeting');

            # Throw Exception If Another User Want to create Moderator Meeting
            if ($meeting->user->id !== Auth::user()->id)
                throw new Exception('This Meetings Moderator is not allowed to You');

            # Create Meeting in BBB Server
            $this->bbb->createEnvironment($meeting);

        }catch (BadResponseException|Exception $e) {
            return back()->with('failed' , 'Can not create meeting :' . $e->getMessage());
        }
        # change Meeting Status in our Database
        $meeting->update([
            'status' => 'Performing'
        ]);
        return back()->with('success' , 'You Can Join Meeting');
    }


    public function list()
    {
        dd($this->bbb->getMeetings());
    }


    public function join(Meeting $meeting, string $password = null): Redirector|RedirectResponse
    {
        $meetingData = unserialize($meeting->meeting_data);

        if (is_null($meetingData['need_password']) && is_null($password))
            $password = Auth::user()->id === $meeting->user_id ? $meetingData['moderatorPassword'] : $meetingData['attendeePassword'];

        $url = $this->bbb->joinMeeting(
            $meetingData['meetingId'] ,
            Auth::user()->name ,
            $password);

        return redirect($url);
    }


    public function end(Meeting $meeting)
    {
        $meetingData = unserialize($meeting->meeting_data);

        # Save Attendance Log in database
//        (new AttendenceController())->setAttendance((array)$this->attendanceLog($meeting));

        # End the session both through the bbb environment and the end button on the single page
        $response = $this->bbb->endMeeting(
            $meetingData['meetingId'] ,
            $meeting->title,
            $meetingData['moderatorPassword']);

        if ($response->getReturnCode() === 'FAILED')
            return back()->with('failed' , $response->getMessage());

        $meeting->update([
            'status' => 'Closed'
        ]);
        return back()->with('success', 'Meeting Closed successfully');
    }


//    public function attendanceLog(Meeting $meeting)
//    {
//        $meetingData = unserialize($meeting->meeting_data);
//
//        if(Auth::user()->id !== $meeting->user_id)
//            return back()->with('failed' , 'You are Not this Meeting Moderator');
//
//        $response = $this->bbb->getMeetingData($meetingData['meetingId'] , $meetingData['moderatorPassword']);
//
//        (new AttendenceController())->setAttendance((array)$response->attendees[0]);
//
//        return $response->attendees[0];
//    }

}
