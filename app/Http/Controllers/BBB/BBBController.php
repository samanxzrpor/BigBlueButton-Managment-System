<?php

namespace App\Http\Controllers\BBB;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Services\BBBService;
use BigBlueButton\Exceptions\BadResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class BBBController extends Controller
{

    public function create(Meeting $meeting)
    {
        try {
            # Create Meeting in BBB Server
            resolve(BBBService::class)->createSession($meeting);
        }catch (BadResponseException $e) {
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
        return resolve(BBBService::class)->getMeetings();
    }


    public function join(Meeting $meeting, string $password = null): Redirector|RedirectResponse
    {
        $meetingData = unserialize($meeting->meeting_data);

        if (is_null($meetingData['need_password']) && is_null($password))
            $password = Auth::user()->id === $meeting->user_id ? $meetingData['moderatorPassword'] : $meetingData['attendeePassword'];

        $url = resolve(BBBService::class)->joinMeeting(
            $meetingData['meetingId'] ,
            $meeting->title,
            $password);

        return redirect($url);
    }


    public function end(Meeting $meeting)
    {
        $meetingData = unserialize($meeting->meeting_data);

        # End the session both through the bbb environment and the end button on the single page
        $response = resolve(BBBService::class)->endMeeting(
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
}
