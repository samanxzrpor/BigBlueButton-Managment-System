<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'=> $this->faker->title(),
            'start_meeting_time' => now(),
            'during_time' => $this->faker->time(),
            'user_id' => function(){
                return User::factory()->create();
            },
            'meeting_data' => serialize($this->faker->shuffleArray())
        ];
    }
}
