<?php

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

// test('employee can be create ', function () {
//    $employeedata = ['employee_id' => '001']
// )};
