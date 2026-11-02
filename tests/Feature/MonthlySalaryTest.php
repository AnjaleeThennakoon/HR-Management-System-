<?php

use App\Models\Employee;
use App\Models\MonthlyAttendanceFinalized;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('monthly salary page successfully loads', function () {
    Payroll::factory()->count(10)->create();

    $response = $this->actingAs($this->user)
        ->get(route('payrolls.index'));

    $response->assertOk()
        ->assertViewHas('payrolls', function ($payrolls): bool {
            return $payrolls->count() === 10;
        });
});

test('can generate monthly salary using finalized attendance', function () {
    $employee = Employee::factory()->create(['salary' => 50000,]);
    MonthlyAttendanceFinalized::factory()->create([
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

    $response = $this->actingAs($this->user)->post(route('payrolls.store'), $payload);

    $response->assertRedirect(route('payrolls.index'));
    $this->assertDatabaseHas('payrolls', [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
        'basic_salary' => 50000,
        'allowances' => 480.77,
        'deductions' => 4086.54,
        'net_salary' => 46394.23,
    ]);
});

test('can generate monthly salary', function () {
    $employee = Employee::factory()->create(['salary' => 50000,]);
    MonthlyAttendanceFinalized::factory()->create([
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
        'total_late_minutes' => 60,
        'total_ot_minutes' => 120,
    ]);
    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->post(route('payrolls.store'), $payload);

    $response->assertRedirect(route('payrolls.index'))
        ->assertSessionHas('success', 'Monthly payroll has been generated.');
    $this->assertDatabaseHas('payrolls', [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
        'basic_salary' => 50000,
        'allowances' => 480.77,
        'deductions' => 240.38,
        'net_salary' => 50240.38,
    ]);

});
