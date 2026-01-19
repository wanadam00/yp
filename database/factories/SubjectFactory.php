<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subject_name' => $this->faker->randomElement([
                'Mathematics',
                'Science',
                'Programming',
                'Database Systems',
                'Networking',
            ]),
            'class_id' => 1,
            // 'description' => $this->faker->sentence(),
        ];
    }
}
