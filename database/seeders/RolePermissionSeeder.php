<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view exams',
            'create exams',
            'edit exams',
            'delete exams',
            'take exams',
            'view results',
            'manage classes',
            'manage subjects',
            'manage students',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $lecturerRole = Role::firstOrCreate(['name' => 'Lecturer']);
        $lecturerRole->givePermissionTo([
            'view exams',
            'create exams',
            'edit exams',
            'delete exams',
            'view results',
            'manage classes',
            'manage subjects',
            'manage students',
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'Student']);
        $studentRole->givePermissionTo([
            'view exams',
            'take exams',
            'view results',
        ]);
    }
}
