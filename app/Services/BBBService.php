<?php

namespace App\Services;

use App\Models\Meeting;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use SimpleXMLElement;


class BBBService
{

    private BigBlueButton $bbb;

    private $meetingParams;


    public function __construct()
    {
        $this->bbb = new BigBlueButton(env('BBB_SERVER_BASE_URL') , env('BBB_SECRET'));
    }


    public function createEnvironment(Meeting $meeting)
    {
        $meetingData = unserialize($meeting->meeting_data);
        $isRecordingTrue = $meetingData['recording'] === 'on' ? true : false;

        # Set Params of BBB Request that is needed
        $this->setMeetingParams($meeting, $meetingData, $isRecordingTrue);

         # Create BBB Session
        $response = $this->bbb->createMeeting($this->meetingParams);

        if ($response->getReturnCode() === 'FAILED')
            return back()->with('failed' , 'Can\'t create room! please contact our administrator.');
        return $response;
    }

    /**
     * @param Meeting $meeting
     * @param mixed $meetingData
     * @param bool $isRecordingTrue
     */
    public function setMeetingParams(Meeting $meeting, mixed $meetingData, bool $isRecordingTrue): void
    {
        $createMeetingParams = new CreateMeetingParameters($meetingData['meetingId'], $meeting->title);
        $createMeetingParams->setAttendeePassword($meetingData['attendeePassword']);
        $createMeetingParams->setModeratorPassword($meetingData['moderatorPassword']);
        $createMeetingParams->setDuration($meeting->during_time);
        $createMeetingParams->setLogoutUrl(route('bbb.end' , [$meeting->id]));

        if ($isRecordingTrue) {
            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);
        }

        $this->meetingParams = $createMeetingParams;
    }


    public function getMeetings(): SimpleXMLElement|RedirectResponse
    {
        $response = $this->bbb->getMeetings();

        if ($response->getReturnCode() !== 'SUCCESS')
            return back()->with('failed' , 'Getting Meetings failed');

        return $response->getRawXml()->meetings->meeting;
    }


    public function joinMeeting(mixed $meetingID,string $name,string $password): string
    {
        $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
        $joinMeetingParams->setRedirect(true);
        return $this->bbb->getJoinMeetingURL($joinMeetingParams);
    }


    public function endMeeting(mixed $meetingID,string $moderator_password)
    {
        $endMeetingParams = new EndMeetingParameters($meetingID, $moderator_password);
        return $this->bbb->endMeeting($endMeetingParams);
    }


    public function getMeetingData(mixed $meetingID,string $moderator_password): SimpleXMLElement|RedirectResponse
    {
        $getMeetingInfoParams = new GetMeetingInfoParameters($meetingID, $moderator_password);
        $response = $this->bbb->getMeetingInfo($getMeetingInfoParams);

        if ($response->getReturnCode() === 'FAILED')
            return back()->with('failed' , $response->getMessage());

        return $response->getRawXml();
    }

}
