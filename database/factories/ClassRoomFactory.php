<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_name' => 'Class ' . $this->faker->randomElement(['A', 'B', 'B1', 'C']),
            'lecturer_id' => 1,
            // 'description' => $this->faker->sentence(),
        ];
    }
}
