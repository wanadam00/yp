<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'lecturer']);
        Role::firstOrCreate(['name' => 'student']);

        $this->command->info('✅ Roles created.');
    }
}
