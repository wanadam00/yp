<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question_text' => $this->faker->sentence(8),
            'question_type' => 'mcq', // default, overridden in seeder
            'marks' => 1,
        ];
    }
}
