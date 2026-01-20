<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;

class QuestionOptionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::all();

        foreach ($questions as $question) {
            if ($question->question_text === "What does HTML stand for?") {
                $options = [
                    ['text' => 'Hyper Text Markup Language', 'is_correct' => true],
                    ['text' => 'High Tech Modern Language', 'is_correct' => false],
                    ['text' => 'Hyper Transfer Main Link', 'is_correct' => false],
                ];
            } else {
                $options = [
                    ['text' => 'Unique Identifier', 'is_correct' => true],
                    ['text' => 'Duplicate Value', 'is_correct' => false],
                    ['text' => 'Null Value', 'is_correct' => false],
                ];
            }

            foreach ($options as $opt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'is_correct' => $opt['is_correct'],
                ]);
            }
        }
    }
}
