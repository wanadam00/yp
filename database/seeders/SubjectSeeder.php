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

        foreach ($classes as $class) {
            Subject::factory(3)->create([
                'class_id' => $class->id,
            ]);
        }

        $this->command->info('✅ Subjects created.');
    }
}
