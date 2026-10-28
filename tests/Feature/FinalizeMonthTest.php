<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\FinalizedMonth;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can finalize the month when every working day is accounted for', function () {
    $employee = Employee::factory()->create();
    for ($day = 1; $day <= 31; $day++) {
        $date = Carbon::create(2026, 10, $day);
        if ($date->isWeekend() || in_array($day, [1, 2], true)) {
            continue;
        }
        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
            'in_time' => '09:00',
            'out_time' => '17:00',
        ]);
    }

    Holiday::factory()->create(['date' => '2026-10-01']);
    Leave::factory()->create([
        'employee_id' => $employee->id,
        'start_date' => '2026-10-02',
        'end_date' => '2026-10-02',
        'status' => 'approved',
    ]);
    $attendance = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->post(route('attendance.finalize-month'), $attendance);

    $response->assertRedirect();
    $this->assertDatabaseHas('finalized_months', [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ]);
});

test('cannot finalize the month when a working day is not accounted for', function () {
    $employee = Employee::factory()->create();

    for ($day = 1; $day <= 31; $day++) {
        $date = Carbon::create(2026, 10, $day);

        if ($date->isWeekend() || $day === 30) {
            continue;
        }

        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
            'in_time' => '09:00',
            'out_time' => '17:00',
        ]);
    }

    $response = $this->actingAs($this->user)->from(route('attendance.monthly-report', [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ]))->post(route('attendance.finalize-month'), [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('month');
    $this->assertDatabaseMissing('finalized_months', [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ]);
});

test('cannot finalize the same month twice', function () {
    $finalized = FinalizedMonth::factory()->create([
        'month' => 10,
        'year' => 2026,
    ]);

    $response = $this->actingAs($this->user)->post(route('attendance.finalize-month'), [
        'employee_id' => $finalized->employee_id,
        'month' => $finalized->month,
        'year' => $finalized->year,
    ]);

    $response->assertSessionHasErrors('month');
    $this->assertDatabaseCount('finalized_months', 1);
});
