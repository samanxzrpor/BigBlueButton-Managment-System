<?php

namespace App\Repositories;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MeetingRepository
{

    public function create(array $data): void
    {
        Meeting::create([
            'title' => $data['title'],
            'start_meeting_time' => $data['start-dateTime'],
            'user_id' => Auth::user()->id,
            'during_time' => $data['during-time'],
            'meeting_data' => serialize([
                'meetingId' => Str::slug($data['title']),
                'attendeePassword' => Str::uuid()->toString(),
                'moderatorPassword' => Str::uuid()->toString(),
                'recording' => $data['recording'] ?? null ,
                'need_password' => $data['need_pass'] ?? null
            ])
        ]);
    }

    /**
     * @param Meeting $meeting
     * @param mixed $trustedData
     * @return void
     */
    public function update(Meeting $meeting, mixed $data): void
    {
        $last_meeting_data = unserialize($meeting->meeting_data);
        $meeting->update([
            'title' => $data['title'],
            'start_meeting_time' => $data['start-dateTime'],
            'user_id' => Auth::user()->id,
            'during_time' => $data['during-time'],
            'meeting_data' => serialize([
                'meetingId' => Str::slug($data['title']),
                'attendeePassword'  => $last_meeting_data['attendeePassword'],
                'moderatorPassword' => $last_meeting_data['moderatorPassword'],
                'recording' => $data['recording'] ?? null ,
                'need_password' => $data['need_pass'] ?? null
            ])
        ]);
    }


    /**
     * @param Meeting $meeting
     * @return string
     */
    public function makeGuestLink(Meeting $meeting): string
    {
        $meetingData = unserialize($meeting->meeting_data);
        $requiredParamsToJoin = [
            'meetingID' => $meetingData['meetingId'],
            'fullName' => Str::random(13),
            'password' => $meetingData['attendeePassword'],
            'redirect' => 'true'
        ];

        $queryBuild = http_build_query($requiredParamsToJoin);
        $checkSum = sha1('join' . $queryBuild . env('BBB_SECRET'));
        return env('BBB_SERVER_BASE_URL') . 'api/join?' . $queryBuild . '&checksum=' . $checkSum;
    }

}
