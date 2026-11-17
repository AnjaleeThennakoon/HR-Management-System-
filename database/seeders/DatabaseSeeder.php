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
            'email' => 'admin@hr.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        $employee = User::create([
            'email' => 'employee@hr.com',
            'password' => Hash::make('password123'),
        ]);
        $employee->assignRole('employee');
    }
}
