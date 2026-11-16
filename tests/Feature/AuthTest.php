<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'employee']);
});

test('guest cannot access admin dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('guest cannot access employee dashboard', function () {
    $response = $this->get('/employee/dashboard');
    $response->assertRedirect('/login');
});

test('admin can login and redirect to admin dashboard', function () {
    $admin = User::factory()->create([
        'email' => 'admin@hr.com',
        'password' => bcrypt('password123'),
    ]);
    $admin->assignRole('admin');

    $response = $this->post('/login', [
        'email' => 'admin@hr.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect('/dashboard');
});

test('admin can access admin dashboard', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get('/dashboard');
    $response->assertStatus(200);
});

test('admin with legacy role can access admin dashboard and routes', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/dashboard')
        ->assertOk();

    $this->actingAs($admin)
        ->get('/departments')
        ->assertOk();
});

test('admin cannot access employee dashboard', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get('/employee/dashboard');
    $response->assertStatus(403);
});

test('employee can login and redirect to employee dashboard', function () {
    $employee = User::factory()->create([
        'email' => 'employee@hr.com',
        'password' => bcrypt('password123'),
    ]);
    $employee->assignRole('employee');

    $response = $this->post('/login', [
        'email' => 'employee@hr.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($employee);
    $response->assertRedirect('/employee/dashboard');
});

test('employee can access employee dashboard', function () {
    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $response = $this->actingAs($employee)->get('/employee/dashboard');
    $response
        ->assertOk()
        ->assertSee(route('attendance.index'))
        ->assertDontSee(route('employees.index'));
});

test('employee visiting dashboard is redirected to employee dashboard', function () {
    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $response = $this->actingAs($employee)->get('/dashboard');
    $response->assertRedirect('/employee/dashboard');
});

test('employee with legacy role is redirected to employee dashboard', function () {
    $employee = User::factory()->create(['role' => 'employee']);

    $this->actingAs($employee)
        ->get('/dashboard')
        ->assertRedirect('/employee/dashboard');

    $this->actingAs($employee)
        ->get('/departments')
        ->assertForbidden();
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
