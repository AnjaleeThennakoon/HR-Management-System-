<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hr.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Departments
        $departments = [
            'Human Resources',
            'Information Technology',
            'Finance',
            'Marketing',
            'Sales',
            'Operations',
            'Customer Service',
            'Research & Development',
            'Legal',
            'Administration',
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}
