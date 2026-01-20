<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ClassRoom::all();
        $subjects = [
            'Web Development',
            'Object-Oriented Programming',
            'Database Systems',
            'Operating Systems',
            'Cyber Security'
        ];

        foreach ($classes as $class) {
            foreach (collect($subjects)->random(2) as $name) {
                Subject::create([
                    'subject_name' => $name,
                    'class_id' => $class->id,
                ]);
            }
        }
    }
}
