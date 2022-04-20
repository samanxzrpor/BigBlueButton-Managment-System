<?php

namespace App\Http\Controllers\BBB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Meetings\AttendenceController;
use App\Models\Attendance;
use App\Models\Meeting;
use App\Services\BBBService;
use BigBlueButton\Exceptions\BadResponseException;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use JsonException;

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


    /**
     * Joined Meeting That created in past
     * @param Meeting $meeting
     * @param string|null $password
     * @return Redirector|RedirectResponse
     */
    public function join(Meeting $meeting, string $password = null): Redirector|RedirectResponse
    {
        $meetingData = unserialize($meeting->meeting_data);

        try {
            if (is_null($meetingData['need_password']) && is_null($password))
                $password = Auth::user()->id === $meeting->user_id ? $meetingData['moderatorPassword'] : $meetingData['attendeePassword'];

            $url = $this->bbb->joinMeeting(
                $meetingData['meetingId'] ,
                Auth::user()->name ,
                $password);

        }catch (\Exception $e) {
            return back()->with('failed' , 'Error In Join Meeting :' . $e->getMessage());
        }

        return redirect($url);
    }


    /**
     * Meeting Moderator Can Finish Meeting and generate Meeting Attendance
     * @param Meeting $meeting
     * @return RedirectResponse
     * @throws JsonException
     */
    public function end(Meeting $meeting): RedirectResponse
    {
        $meetingData = unserialize($meeting->meeting_data);

        if(Auth::user()->id !== $meeting->user_id)
            return back()->with('failed' , 'You are Not this Meeting Moderator');

        # Save Attendance Log in database
        $this->setAttendanceLog($meeting);

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


    /**
     * @param Meeting $meeting
     * @return void
     * @throws JsonException
     */
    public function setAttendanceLog(Meeting $meeting): void
    {
        $meetingData = unserialize($meeting->meeting_data);

        #Get All Users That Have been present In Meeting From Meeting Information
        $attendeesToSave = $this->getAttendeesFromMeeting($meetingData);

        # save Attendance Log in Database
        Attendance::create([
            'meeting_id' => $meeting->id,
            'users_data' => serialize($attendeesToSave)
        ]);
    }


    /**
     * Get All Users That Have been present In Meeting From Meeting Information
     * @param array $data
     * @return array
     * @throws JsonException
     */
    public function getAttendeesFromMeeting(array $data): array
    {
        $attendeesToSave = [];

        $response = $this->bbb->getMeetingData($data['meetingId'] , $data['moderatorPassword']);

        foreach ($response->attendees[0] as $attendee) {

            $json = json_encode($attendee, JSON_THROW_ON_ERROR);
            $attendee = json_decode($json, TRUE, 512, JSON_THROW_ON_ERROR);
            $attendeesToSave[] = [
                'user_session' => $attendee['userID'],
                'name' => $attendee['fullName'],
            ];
        }
        return $attendeesToSave;
    }

}
