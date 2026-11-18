<?php

use App\Models\User;
use Database\Seeders\EmployeeRoleSeeder;
use Database\Seeders\EmployeeUsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

test('employee users seeder creates employee user and assigns role idempotently', function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->seed(EmployeeRoleSeeder::class);
    $this->seed(EmployeeUsersSeeder::class);
    $this->seed(EmployeeUsersSeeder::class);

    expect(User::where('email', 'employee@hr.com')->count())->toBe(1);

    $user = User::where('email', 'employee@hr.com')->firstOrFail();

    expect(Hash::check('password123', $user->password))->toBeTrue();
    expect($user->hasRole('employee'))->toBeTrue();
});
