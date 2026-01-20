<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $exams = Exam::all();

        foreach ($exams as $exam) {
            // Create 3 questions per exam
            Question::create([
                'exam_id' => $exam->id,
                'question_text' => "What does HTML stand for?",
                'question_type' => 'mcq',
                'marks' => 10,
            ]);

            Question::create([
                'exam_id' => $exam->id,
                'question_text' => "Which of the following is a primary key attribute?",
                'question_type' => 'mcq',
                'marks' => 10,
            ]);
        }
    }
}
