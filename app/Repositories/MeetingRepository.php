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
                'attendeePassword' => Str::uuid(),
                'moderatorPassword' => Str::uuid(),
                'recording' => $data['recording']
            ])
        ]);
    }

    /**
     * @param Meeting $meeting
     * @param mixed $trustedData
     * @return void
     */
    public function update(Meeting $meeting, mixed $trustedData): void
    {
        $meeting->update([
            'title' => $trustedData['title'] ?? $meeting->title,
            'start_meeting_time' => $trustedData['start_dateTime'] ?? $meeting->start_meeting_time,
            'user_id' => Auth::user()->id,
            'during_time' => $trustedData['during-time'] ?? $meeting->during_time,
        ]);
    }

}
