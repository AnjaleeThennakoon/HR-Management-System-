<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@hr.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        $employee = User::create([
            'name' => 'John Employee',
            'email' => 'employee@hr.com',
            'password' => Hash::make('password123'),
        ]);
        $employee->assignRole('employee');
    }
}
