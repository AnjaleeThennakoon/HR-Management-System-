<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        $adminRole = Role::findOrCreate('admin', 'web');

        $permissions = Permission::where('guard_name', 'web')->get();

        $adminRole->syncPermissions($permissions);

    }
}
