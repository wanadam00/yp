<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use App\Models\User;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::role('lecturer')->first();

        $classes = [
            ['class_name' => 'CS Year 1 - Foundations'],
            ['class_name' => 'CS Year 2 - Software Engineering'],
            ['class_name' => 'CS Year 3 - Data Science'],
        ];

        foreach ($classes as $class) {
            ClassRoom::create([
                'class_name' => $class['class_name'],
                'lecturer_id' => $lecturer->id, // Linking to the lecturer
            ]);
        }
        $this->command->info('✅ CS Classes created and assigned to Lecturer.');
    }
}
