<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeUsersSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('employee', 'web');

        $employee = User::firstOrCreate(
            ['email' => 'employee@hr.com'],
            [
                'password' => Hash::make('password123'),
            ]
        );

        $employee->assignRole('employee');
    }
}
