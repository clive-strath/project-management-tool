<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'owner_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['active', 'archived', 'completed']),
            'due_date' => $this->faker->dateTimeBetween('now', '+6 months'),
        ];
    }
}
