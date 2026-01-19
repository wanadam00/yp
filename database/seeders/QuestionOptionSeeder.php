<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;

class QuestionOptionSeeder extends Seeder
{
    public function run(): void
    {
        $mcqQuestions = Question::where('question_type', 'mcq')->get();

        foreach ($mcqQuestions as $question) {
            $correctIndex = rand(0,3);
            for ($i=0; $i<4; $i++) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => "Option ".($i+1),
                    'is_correct' => $i === $correctIndex,
                ]);
            }
        }

        $this->command->info('✅ Question options created.');
    }
}
