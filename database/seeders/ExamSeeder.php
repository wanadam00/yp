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
            Exam::factory(2)->create([
                'title' => "Exam for {$subject->name}",
                'description' => "This is an exam for the subject {$subject->name}.",
                'duration_minutes' => 60,
                'start_time' => now()->addDays(7),
                'end_time' => now()->addDays(7)->addMinutes(60),
                'created_by' => $lecturer->id,
                'duration_minutes' => 15,
            ]);
        }

        $this->command->info('✅ Exams created.');
    }
}
