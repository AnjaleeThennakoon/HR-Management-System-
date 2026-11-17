<?php

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('admin can create an employee account', function () {
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
        'name' => 'Jane Employee',
        'email' => 'jane.employee@example.com',
        'employee_id' => 'EMP001',
    ]);
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

test('only admin can create users;user cannot create users', function () {
    Role::create(['name' => 'admin']);
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

test('employee and admin can update their own password', function (string $role) {
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

test('admin and employee can update employee account details', function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $employee = User::factory()->create([
        'email' => 'employee@example.com',
        'password' => bcrypt('password123'),
    ]);
    $employee->assignRole('employee');
    $this->actingAs($admin)
        ->put(route('users.update', $employee->id), [
            'name' => 'Updated Employee',
            'email' => 'updated@example.com',
            'password' => 'newpassword123',
        ])
        ->assertRedirect();
    $employee->refresh();
    $this->assertDatabaseHas('users', [
        'id' => $employee->id,
        'name' => 'Updated Employee',
        'email' => 'updated@example.com',
    ]);

    expect(
        Hash::check('newpassword123', $employee->password)
    )->toBeTrue();

    $this->actingAs($employee)
        ->put(route('users.update', $employee->id), [
            'name' => 'Employee Self Update',
            'email' => 'selfupdate@example.com',
            'current_password' => 'newpassword123',
            'password' => 'finalpassword123',
        ])
        ->assertRedirect();

    $employee->refresh();
    $this->assertDatabaseHas('users', [
        'id' => $employee->id,
        'name' => 'Employee Self Update',
        'email' => 'selfupdate@example.com',
    ]);
    expect(Hash::check('finalpassword123', $employee->password))->toBeTrue();
});

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
        'name' => $anotherEmployee->name,
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

test('admin can access users index page without exposing admin accounts', function () {
    Role::create(['name' => 'admin']);
    $admin = User::factory()->create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
    ]);
    $admin->assignRole('admin');

    $employeeUser = User::factory()->create([
        'name' => 'John Employee',
        'email' => 'john.employee@example.com',
    ]);

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('Users &amp; Employee Accounts', false)
        ->assertDontSee('admin@example.com')
        ->assertSee('John Employee')
        ->assertSee('john.employee@example.com')
        ->assertDontSee('Password</th>', false);
});

test('employee cannot access users index page', function () {
    Role::create(['name' => 'employee']);
    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $this->actingAs($employee)
        ->get(route('users.index'))
        ->assertForbidden();
});

test('admin can update employee without changing password', function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $employee = User::factory()->create([
        'name' => 'Initial Name',
        'email' => 'initial@example.com',
        'password' => bcrypt('existingPassword123'),
    ]);
    $employee->assignRole('employee');

    $this->actingAs($admin)
        ->put(route('users.update', $employee->id), [
            'name' => 'Modified Name',
            'email' => 'modified@example.com',
        ])
        ->assertRedirect();

    $employee->refresh();
    expect($employee->name)->toBe('Modified Name');
    expect($employee->email)->toBe('modified@example.com');
    expect(Hash::check('existingPassword123', $employee->password))->toBeTrue();
});

test('admin can create a user with username and employee is listed in dropdown', function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $employee = Employee::factory()->create([
        'employee_id' => 'EMP999',
        'first_name' => 'Alice',
        'last_name' => 'Smith',
    ]);

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('EMP999 - Alice Smith')
        ->assertSee('Username');

    $this->actingAs($admin)
        ->post(route('users.store'), [
            'name' => 'Alice Smith',
            'email' => 'alicesmith',
            'password' => 'password123',
            'employee_id' => $employee->employee_id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'name' => 'Alice Smith',
        'email' => 'alicesmith',
    ]);

    auth()->logout();

    $this->post('/login', [
        'email' => 'alicesmith',
        'password' => 'password123',
    ])->assertRedirect('/employee/dashboard');
});

test('users index excludes all admin accounts and lists employee accounts', function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);

    $currentAdmin = User::factory()->create([
        'name' => 'Current Admin',
        'email' => 'currentadmin',
    ]);
    $currentAdmin->assignRole('admin');

    $otherAdmin = User::factory()->create([
        'name' => 'Another Admin',
        'email' => 'anotheradmin',
    ]);
    $otherAdmin->assignRole('admin');

    $employeeUser = User::factory()->create([
        'name' => 'Worker Bee',
        'email' => 'workerbee',
    ]);
    $employeeUser->assignRole('employee');

    $this->actingAs($currentAdmin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('Worker Bee')
        ->assertDontSee('currentadmin')
        ->assertDontSee('anotheradmin');
});

test('validation fails with friendly error messages on empty user fields', function () {
    Role::create(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)
        ->post(route('users.store'), [
            'name' => '',
            'email' => '',
            'password' => '',
            'employee_id' => '',
        ]);

    $response->assertSessionHasErrors(['name', 'email', 'password', 'employee_id']);
});

test('employee dashboard displays employee portal details', function () {
    Role::create(['name' => 'employee']);

    $employee = Employee::factory()->create([
        'first_name' => 'David',
        'last_name' => 'Miller',
        'employee_id' => 'EMP-777',
    ]);

    $user = User::factory()->create([
        'name' => 'David Miller',
        'email' => 'davidmiller',
        'password' => bcrypt('password123'),
    ]);
    $user->assignRole('employee');

    $this->actingAs($user)
        ->get(route('employee.dashboard'))
        ->assertOk()
        ->assertSee('Welcome, David Miller!')
        ->assertSee('EMP-777')
        ->assertSee('davidmiller')
        ->assertSee('Account Security');
});
