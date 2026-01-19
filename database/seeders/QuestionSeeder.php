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
            // 3 MCQ questions
            Question::factory(3)->create([
                'exam_id' => $exam->id,
                // 'question_type' => 'mcq',
                'marks' => 1
            ]);

            // 2 Open text questions
            Question::factory(2)->create([
                'exam_id' => $exam->id,
                // 'question_type' => 'text',
                'marks' => 2
            ]);
        }

        $this->command->info('✅ Questions created.');
    }
}
