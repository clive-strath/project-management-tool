<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $started = $this->faker->dateTimeBetween('-1 month', 'now');
        $duration = $this->faker->numberBetween(15, 480); // 15 mins to 8 hours
        $ended = (clone $started)->modify("+{$duration} minutes");

        return [
            'task_id' => \App\Models\Task::factory(),
            'user_id' => \App\Models\User::factory(),
            'started_at' => $started,
            'ended_at' => $ended,
            'duration' => $duration,
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
