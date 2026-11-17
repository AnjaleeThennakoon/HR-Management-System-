<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmployeeRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeRole = Role::findOrCreate('employee', 'web');

        $permissions = [
            'employees.store',
            'employees.update',

            'attendance.store',

            'leaves.store',
            'leaves.update',
        ];
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
        $employeeRole->syncPermissions($permissions);

    }
}
