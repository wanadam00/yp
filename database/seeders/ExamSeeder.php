<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::role('lecturer')->first();
        $subjects = Subject::all();

        foreach ($subjects as $subject) {
            Exam::create([
                'title' => "{$subject->subject_name} Final Quiz",
                'description' => "Assess knowledge in {$subject->subject_name}.",
                'subject_id' => $subject->id,
                'created_by' => $lecturer->id,
                'duration_minutes' => 30,
            ]);
        }
    }
}
