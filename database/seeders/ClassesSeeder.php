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

        ClassRoom::factory(2)->create([
            'lecturer_id' => $lecturer->id,
        ])->each(function ($class) {
            $this->command->info("Class {$class->class_name} created.");
        });
    }
}
