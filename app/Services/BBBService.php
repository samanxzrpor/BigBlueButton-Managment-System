<?php

namespace App\Services;

use App\Models\Meeting;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Support\Str;


class BBBService
{

    private $bbb;



    public function __construct()
    {
        $this->bbb = new BigBlueButton(env('BBB_SERVER_BASE_URL') , env('BBB_SECRET'));
    }


    public function createSession(Meeting $meeting)
    {
        $meetingData = unserialize($meeting->meeting_data);
        $isRecordingTrue = $meetingData['recording'] === 'on' ? true : false;


        # Set Params of BBB Request that is needed
        $createMeetingParams = $this->getCreateMeetingParams($meeting, $meetingData, $isRecordingTrue);

        # Create BBB Session
        $response = $this->bbb->createMeeting($createMeetingParams);

        if ($response->getReturnCode() === 'FAILED')
            return back()->with('failed' , 'Can\'t create room! please contact our administrator.');

    }

    /**
     * @param Meeting $meeting
     * @param mixed $meetingData
     * @param bool $isRecordingTrue
     * @return CreateMeetingParameters
     */
    public function getCreateMeetingParams(Meeting $meeting, mixed $meetingData, bool $isRecordingTrue): CreateMeetingParameters
    {
        $createMeetingParams = new CreateMeetingParameters($meetingData['meetingId'], $meeting->title);
        $createMeetingParams->setAttendeePassword($meetingData['attendeePassword']);
        $createMeetingParams->setModeratorPassword($meetingData['moderatorPassword']);
        $createMeetingParams->setDuration($meeting->during_time);
        $createMeetingParams->setLogoutUrl(route('bbb.close'));

        if ($isRecordingTrue) {
            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);
        }

        return $createMeetingParams;
    }


    public function getMeetings()
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

}
