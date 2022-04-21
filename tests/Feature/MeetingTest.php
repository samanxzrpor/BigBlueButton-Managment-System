<?php


use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Ramsey\Collection\Collection;
use Tests\TestCase;


class MeetingTest extends TestCase
{
    use RefreshDatabase;


    public function testGetAllMeetingsInDatabaseAndShowThem()
    {
        $user = User::factory()->create();
        $meetings = Meeting::factory(20)->create();
        $response = $this->actingAs($user)->get(route('meetings.index'));
        $response->assertSuccessful();
        $response->assertSee($meetings[0]->title);
    }

    public function testCrateMeetingPage()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('meetings.create'));
        $response->assertSuccessful();
        $response->assertSee('Create New Seminar Session');
    }

    public function testThatStoreNewMeetingInDatabaseWithTrueInfo()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('meetings.store'),[
            'title' => 'Test New Meeting',
            'user_id' => $user->id,
            'meeting_data' => serialize([]),
            'start-dateTime' => now(),
            'during-time' => 20
        ])->isSuccessful();

        $this->assertDatabaseHas('meetings' , ['title' => 'Test New Meeting']);
    }

    public function testThatStoreNewMeetingInDatabaseWithIncorrectInfo()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('meetings.store'),[
            'title' => '',
            'user_id' => $user->id,
            'meeting_data' => '',
            'start-dateTime' => now(),
            'during-time' => 20
        ])->isInvalid();

        $this->assertDatabaseMissing('meetings' , ['title' => 'Test New Meeting']);
    }
}
