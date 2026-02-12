<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_list_id' => \App\Models\TaskList::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'assignee_id' => \App\Models\User::factory(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => $this->faker->randomElement(['todo', 'in_progress', 'review', 'done']),
            'due_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'position' => 0,
        ];
    }
}
