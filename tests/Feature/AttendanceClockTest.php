<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->employee = Employee::factory()->create();
});

test('employee can check in once per day', function () {
    $this->actingAs($this->user)
        ->post(route('attendance.check-in'), ['employee_id' => $this->employee->id])
        ->assertRedirect(route('attendance.index'))
        ->assertSessionHas('success', 'Employee checked in successfully.');

    $this->assertDatabaseHas('attendances', [
        'employee_id' => $this->employee->id,
        'date' => now()->toDateString(),
        'in_time' => now()->format('H:i:s'),
        'out_time' => null,
    ]);

    $this->actingAs($this->user)
        ->post(route('attendance.check-in'), ['employee_id' => $this->employee->id])
        ->assertSessionHasErrors('employee_id');

    $this->assertDatabaseCount('attendances', 1);
});

test('employee can check out after checking in', function () {
    Attendance::factory()->create([
        'employee_id' => $this->employee->id,
        'date' => now()->toDateString(),
        'in_time' => now()->subHours(8)->format('H:i:s'),
        'out_time' => null,
    ]);

    $this->actingAs($this->user)
        ->post(route('attendance.check-out'), ['employee_id' => $this->employee->id])
        ->assertRedirect(route('attendance.index'))
        ->assertSessionHas('success', 'Employee checked out successfully.');

    $this->assertDatabaseHas('attendances', [
        'employee_id' => $this->employee->id,
        'date' => now()->toDateString(),
        'out_time' => now()->format('H:i:s'),
    ]);
});

test('employee cannot check out without an open check in', function () {
    $this->actingAs($this->user)
        ->post(route('attendance.check-out'), ['employee_id' => $this->employee->id])
        ->assertSessionHasErrors('employee_id');

    $this->assertDatabaseCount('attendances', 0);
});
