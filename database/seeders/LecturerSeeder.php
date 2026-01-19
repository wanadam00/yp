<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class LecturerSeeder extends Seeder
{
    public function run(): void
    {
        $lecturerRole = Role::where('name', 'lecturer')->first();

        User::factory(2)->create()->each(function ($user) use ($lecturerRole) {
            $user->assignRole($lecturerRole);
        });

        $this->command->info('✅ Lecturers created.');
    }
}
