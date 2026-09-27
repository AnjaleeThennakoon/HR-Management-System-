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

    $employee = Employee::factory()->make([
        'employee_id' => 'EMP001',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'date_of_birth' => '1995-05-15',
        'gender' => 'Male',
        'nic' => '123456789012',
        'phone' => '0771234567',
        'address' => 'Colombo, Sri Lanka',
    ]);

    $response = $this->actingAs($this->user)->post('/employees', $employee->toArray());
    $response->assertStatus(302);

    $response->assertRedirect('/employees');
    $this->assertDatabaseCount('employees', 1);
    $this->assertDatabaseHas('employees', [
        'employee_id' => 'EMP001',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'department_id' => $employee->department_id,
        'designation_id' => $employee->designation_id,
        'date_of_birth' => '1995-05-15',
        'gender' => 'Male',
        'nic' => '123456789012',
        'phone' => '0771234567',
        'address' => 'Colombo, Sri Lanka',
    ]);
});

test('an employee can be created without a designation', function () {
    $employee = Employee::factory()->make(['designation_id' => null]);

    $response = $this->actingAs($this->user)->post('/employees', $employee->toArray());

    $response->assertRedirect('/employees');
    $this->assertDatabaseHas('employees', [
        'employee_id' => $employee->employee_id,
        'designation_id' => null,
    ]);
});

test('employee form errors are displayed on the employee page', function () {
    $employee = Employee::factory()->make(['first_name' => '']);

    $response = $this->from('/employees')
        ->followingRedirects()
        ->actingAs($this->user)
        ->post('/employees', $employee->toArray());

    $response->assertOk()
        ->assertSeeText('The first name field is required.');
});

test('an invalid designation is rejected and its error is displayed', function () {
    $employee = Employee::factory()->make(['designation_id' => 999999]);

    $response = $this->from('/employees')
        ->followingRedirects()
        ->actingAs($this->user)
        ->post('/employees', $employee->toArray());

    $response->assertOk()
        ->assertSeeText('The selected designation id is invalid.');
    $this->assertDatabaseCount('employees', 0);
});

test('an employee can be updated', function () {
    $employee = Employee::factory()->create();
    $department = Department::factory()->create();
    $designation = Designation::factory()->create();
    $employeedata = [
        'employee_id' => $employee->employee_id,
        'department_id' => $department->id,
        'designation_id' => $designation->id,
        'first_name' => 'Bob',
        'last_name' => 'Crime',
        'date_of_birth' => $employee->date_of_birth,
        'gender' => $employee->gender,
        'nic' => $employee->nic,
        'phone' => '0771234567',
        'address' => 'Australia',
    ];

    $response = $this->actingAs($this->user)->put("/employees/{$employee->id}", $employeedata);

    $response->assertStatus(302);
    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'department_id' => $department->id,
        'designation_id' => $designation->id,
        'first_name' => 'Bob',
        'last_name' => 'Crime',
        'phone' => '0771234567',
        'address' => 'Australia',
    ]);
});

test('an employee can be deleted', function () {
    $employee = Employee::factory()->create();

    $this->assertDatabaseHas('employees', ['id' => $employee->id]);
    $this->actingAs($this->user)->delete("/employees/{$employee->id}");

    $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
});

test('employee nic must be 12 digits or 10 digits followed by v', function () {
    $employee = Employee::factory()->make(['nic' => '1234561']);

    $response = $this->actingAs($this->user)->post('/employees', $employee->toArray());

    $response->assertSessionHasErrors('nic');
});

test('employee nic accepts 10 digits followed by v', function () {
    $employee = Employee::factory()->make(['nic' => '1234567890v']);

    $response = $this->actingAs($this->user)->post('/employees', $employee->toArray());

    $response->assertRedirect('/employees');
    $this->assertDatabaseHas('employees', ['nic' => '1234567890v']);
});

test('employee phone must contain 10 digits', function () {
    $employee = Employee::factory()->make(['phone' => '1234561']);

    $response = $this->actingAs($this->user)->post('/employees', $employee->toArray());

    $response->assertSessionHasErrors('phone');

});

test('employee nic must be unique', function () {
    $employee1 = Employee::factory()->create(['nic' => '123456789012']);
    $employee2 = Employee::factory()->make(['nic' => $employee1->nic]);

    $response = $this->actingAs($this->user)->post('/employees', $employee2->toArray());

    $response->assertStatus(302);
    $response->assertSessionHasErrors('nic');
    $this->assertDatabaseCount('employees', 1);

});

test('employee id  must be unique', function () {
    $employee1 = Employee::factory()->create(['employee_id' => 'EMP001']);
    $employee2 = Employee::factory()->make(['employee_id' => $employee1->employee_id]);

    $response = $this->actingAs($this->user)->post('/employees', $employee2->toArray());

    $response->assertSessionHasErrors('employee_id');
    $this->assertDatabaseCount('employees', 1);
});

test('employee date of birth must be valid', function () {
    $employee = Employee::factory()->make(['date_of_birth' => '2027-08-08']);

    $response = $this->actingAs($this->user)->post('/employees', $employee->toArray());

    $response->assertSessionHasErrors('date_of_birth');
});

test('employee gender must be valid', function () {
    $employee = Employee::factory()->make(['gender' => 'Other']);

    $response = $this->actingAs($this->user)->post('/employees', $employee->toArray());

    $response->assertSessionHasErrors('gender');
});
