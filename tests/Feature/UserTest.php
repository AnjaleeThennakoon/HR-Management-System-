<?php

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('admin can create an employee account', function () {
    expect(Schema::hasColumn('users', 'name'))->toBeFalse();

    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $employee = Employee::factory()->create([
        'employee_id' => 'EMP001',
        'first_name' => 'Jane',
        'last_name' => 'Employee',
    ]);
    $userData = [
        'name' => 'Jane Employee',
        'email' => 'jane.employee@example.com',
        'password' => 'password123',
        'employee_id' => $employee->employee_id,
    ];

    $this->actingAs($admin)
        ->post(route('users.store'), $userData)
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'email' => 'jane.employee@example.com',
        'employee_id' => 'EMP001',
    ]);

    $user = User::where('email', 'jane.employee@example.com')->firstOrFail();
    expect($user->name)->toBe('Jane Employee');

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('Jane Employee');
});

test('admin cannot create more than one account for the same employee ID', function () {
    Role::create(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $employee = Employee::factory()->create([
        'employee_id' => 'EMP002',
        'first_name' => 'John',
        'last_name' => 'Employee',
    ]);

    $this->actingAs($admin)
        ->post(route('users.store'), [
            'name' => 'John Employee',
            'email' => 'john.employee@example.com',
            'password' => 'password123',
            'employee_id' => $employee->employee_id,
        ])
        ->assertRedirect();

    $this->actingAs($admin)
        ->post(route('users.store'), [
            'name' => 'John Employee',
            'email' => 'john.employee.duplicate@example.com',
            'password' => 'password123',
            'employee_id' => $employee->employee_id,
        ])
        ->assertSessionHasErrors([
            'employee_id' => 'This employee already has a login account.',
        ]);

    expect(User::where('employee_id', $employee->employee_id)->count())->toBe(1);
});

test('employee can login and access dashboard', function () {
    Role::create(['name' => 'employee']);
    $employee = User::factory()->create([
        'email' => 'jane.employee@example.com',
        'password' => bcrypt('password123'),
    ]);
    $employee->assignRole('employee');

    $this->post('/login', ['email' => 'jane.employee@example.com', 'password' => 'password123'])->assertRedirect('/employee/dashboard');

    $this->assertAuthenticatedAs($employee);
    $this->get(route('employee.dashboard'))->assertOk();
});

test('only admin can create users; user cannot create users', function () {
    Role::create(['name' => 'employee']);

    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $userData = [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password123',
    ];

    $this->actingAs($employee)
        ->post(route('users.store'), $userData)
        ->assertForbidden();
});

test('guest cannot create users', function () {
    $this->post(route('users.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
    ])
        ->assertRedirect(route('login'));
});

test('employee and admin can update their own password ', function (string $role) {
    Role::create(['name' => $role]);
    $employee = User::factory()->create([
        'password' => bcrypt('oldpassword'),
    ]);
    $employee->assignRole($role);
    $userData = [
        'name' => $employee->name,
        'email' => $employee->email,
        'current_password' => 'oldpassword',
        'password' => 'newpassword123',
    ];

    $this->actingAs($employee)
        ->put(route('users.update', $employee->id), $userData)
        ->assertRedirect();

    $employee->refresh();
    expect(Hash::check('newpassword123', $employee->password))->toBeTrue();
})->with(['employee', 'admin']);

test('employee cannot update another employee account', function () {
    Role::create(['name' => 'employee']);
    $employee = User::factory()->create();
    $employee->assignRole('employee');
    $anotherEmployee = User::factory()->create();

    $this->actingAs($employee)
        ->put(route('users.update', $anotherEmployee->id), [
            'name' => 'Unauthorized Update',
            'email' => 'unauthorized@example.com',
            'password' => 'newpassword123',
        ])
        ->assertForbidden();

    $this->assertDatabaseHas('users', [
        'id' => $anotherEmployee->id,
        'email' => $anotherEmployee->email,
    ]);
});

test('admin can delete an employee account', function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $this->actingAs($admin)
        ->delete(route('users.destroy', $employee->id))
        ->assertRedirect();

    $this->assertDatabaseMissing('users', [
        'id' => $employee->id,
    ]);
});

test('employee cannot access users dashboard', function () {
    Role::create(['name' => 'employee']);
    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $this->actingAs($employee)
        ->get(route('users.index'))
        ->assertForbidden();
});
