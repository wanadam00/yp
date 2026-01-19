<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'duration_minutes' => 15,
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(1)->addMinutes(15),
            'created_by' => 1,
        ];
    }
}
