<?php

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('employee list page loads successfully', function () {
    Employee::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/employees');

    $response->assertOk()
        ->assertViewHas('employees', function ($employees): bool {
            return $employees->count() === 10;
        });
});

test('an employee can be created', function () {
    $employeeData = [
        'employee_id' => 'EMP001',
        'department_id' => Department::factory()->create()->id,
        'designation_id' => Designation::factory()->create()->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'date_of_birth' => '1995-05-15',
        'gender' => 'Male',
        'nic' => '951234567V',
        'phone' => '0771234567',
        'address' => 'Colombo, Sri Lanka',
    ];

    $response = $this->actingAs($this->user)
        ->post('/employees', $employeeData);

    $response->assertStatus(302);
    $response->assertRedirect('/employees');
    $this->assertDatabaseCount('employees', 1);
    $this->assertDatabaseHas('employees', [
        'employee_id' => 'EMP001',
        'department_id' => $employeeData['department_id'],
        'designation_id' => $employeeData['designation_id'],
        'first_name' => 'John',
        'last_name' => 'Doe',
        'date_of_birth' => '1995-05-15',
        'gender' => 'Male',
        'nic' => '951234567V',
        'phone' => '0771234567',
        'address' => 'Colombo, Sri Lanka',
    ]);
});
