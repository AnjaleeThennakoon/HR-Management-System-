<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('employee', 'web');

        $admin = User::firstOrCreate(
            ['email' => 'admin@hr.com'],
            [
                'password' => Hash::make('password123'),
            ]
        );

        $admin->assignRole('admin');

        $employee = User::firstOrCreate(
            ['email' => 'employee@hr.com'],
            [
                'password' => Hash::make('password123'),
            ]
        );

        $employee->assignRole('employee');
    }
}
