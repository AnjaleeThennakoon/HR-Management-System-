<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);
});

test('admin can open the admin profile from the account section', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('admin.profile'))
        ->assertOk()
        ->assertSee('Administrator Profile')
        ->assertSee('Change Password')
        ->assertSee(route('admin.profile'));
});

test('employee can open the employee profile from the account section', function () {
    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $this->actingAs($employee)
        ->get(route('employee.profile'))
        ->assertOk()
        ->assertSee('Employee Profile')
        ->assertSee('Change Password')
        ->assertSee(route('employee.profile'));
});

test('users cannot open the other role profile page', function (string $role, string $profileRoute) {
    $user = User::factory()->create();
    $user->assignRole($role);

    $this->actingAs($user)
        ->get(route($profileRoute))
        ->assertForbidden();
})->with([
    'employee cannot open admin profile' => ['employee', 'admin.profile'],
    'admin cannot open employee profile' => ['admin', 'employee.profile'],
]);

test('users can change their password from their role-specific profile', function (string $role, string $profileRoute) {
    $user = User::factory()->create([
        'password' => 'old-password-123',
    ]);
    $user->assignRole($role);

    $this->actingAs($user)
        ->from(route($profileRoute))
        ->put(route('users.update', $user->id), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'old-password-123',
            'password' => 'new-password-123',
        ])
        ->assertRedirect(route($profileRoute))
        ->assertSessionHas('success', 'Employee account updated successfully.');

    $user->refresh();
    expect(Hash::check('new-password-123', $user->password))->toBeTrue();
})->with([
    'admin profile' => ['admin', 'admin.profile'],
    'employee profile' => ['employee', 'employee.profile'],
]);
