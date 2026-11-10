<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
    $response->assertStatus(200);
});

test('employee cannot access admin dashboard', function () {
    $employee = User::factory()->create();
    $employee->assignRole('employee');

    $response = $this->actingAs($employee)->get('/dashboard');
    $response->assertStatus(403);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
