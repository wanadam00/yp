<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * -----------------------------
         * Create Roles
         * -----------------------------
         */
        $lecturerRole = Role::firstOrCreate([
            'name' => 'lecturer',
            'guard_name' => 'web',
        ]);

        $studentRole = Role::firstOrCreate([
            'name' => 'student',
            'guard_name' => 'web',
        ]);

        /**
         * -----------------------------
         * (Optional) Create Permissions
         * -----------------------------
         */
        $permissions = [
            'create exams',
            'manage classes',
            'manage subjects',
            'view results',
            'mark answers',

            'view exams',
            'start exam',
            'submit exam',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Assign permissions to roles
        $lecturerRole->syncPermissions([
            'create exams',
            'manage classes',
            'manage subjects',
            'view results',
            'mark answers',
        ]);

        $studentRole->syncPermissions([
            'view exams',
            'start exam',
            'submit exam',
        ]);

        /**
         * -----------------------------
         * Create Lecturer User
         * -----------------------------
         */
        $lecturer = User::firstOrCreate(
            ['email' => 'lecturer@example.com'],
            [
                'name' => 'Lecturer Account',
                'password' => bcrypt('password'),
            ]
        );

        $lecturer->assignRole($lecturerRole);

        /**
         * -----------------------------
         * Create Student User
         * -----------------------------
         */
        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student Account',
                'password' => bcrypt('password'),
            ]
        );

        $student->assignRole($studentRole);
        $this->call([
            RoleSeeder::class,
            LecturerSeeder::class,
            StudentSeeder::class,
            ClassesSeeder::class,
            SubjectSeeder::class,
            ExamSeeder::class,
            QuestionSeeder::class,
            QuestionOptionSeeder::class,
        ]);
    }
}
