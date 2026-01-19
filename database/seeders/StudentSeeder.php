<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();

        User::factory(10)->create()->each(function ($user) use ($studentRole) {
            $user->assignRole($studentRole);
        });

        $this->command->info('✅ Students created.');
    }
}
