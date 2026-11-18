<?php

use Database\Seeders\EmployeeRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

test('employee role has only the configured permissions', function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    $this->seed(EmployeeRoleSeeder::class);
    $this->seed(EmployeeRoleSeeder::class);

    $role = Role::findByName('employee', 'web');

    expect($role->permissions->pluck('name')->sort()->values()->all())->toBe([
        'attendance.view-own-attendDays',
        'departments.view-own',
        'designations.view-own',
        'employees.view-own',
        'holidays.view',
        'leaves.destroy',
        'leaves.store',
        'leaves.update',
        'payrolls.download-own',
        'payrolls.view-own',
    ]);
});
