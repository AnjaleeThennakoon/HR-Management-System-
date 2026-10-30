<?php

use App\Models\Employee;
use App\Models\Leave;
use App\Models\MonthlyAttendanceFinalize;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('monthly salary  page successfully loads', function () {
    Payroll::factory()->count(10)->create([
        'employee_id' => Employee::factory(),
    ]);

    $response = $this->actingAs($this->user)->get('/payrolls');

    $response->assertOk();
});

test('can generate monthly salary using finalized attendance', function () {
    $employee = Employee::factory()->create(['basic_salary' => 50000,]);
    MonthlyAttendanceFinalize::factory()->create([
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
        'total_late_minutes' => 60,
        'total_ot_minutes' => 120,
        'total_no_pay_days' => 2,
    ]);
    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->post(route('payroll.generate'), $payload);

    $response->assertRedirect(route('payrolls.index'))
        ->assertSessionHas('success', 'Monthly payroll has been generated.');
    $this->assertDatabaseHas('payrolls', [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
        'basic_salary' => 50000,
        'allowances' => 0,
        'deductions' => 0,
        'net_salary' => 50000,
    ]);

});
