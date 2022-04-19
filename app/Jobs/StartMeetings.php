<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Services\BBBService;
use BigBlueButton\Exceptions\BadResponseException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StartMeetings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private BBBService $bbb;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bbb = new BBBService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach (Meeting::all() as $meeting) {
            if ($meeting->start_meeting_time === now()->format('Y-m-d H:i').':00') {
                # Create Meeting in BBB Server
                $this->bbb->createEnvironment($meeting);

                # change Meeting Status in our Database
                $meeting->update([
                    'status' => 'Performing'
                ]);
            }
        }
    }
}
