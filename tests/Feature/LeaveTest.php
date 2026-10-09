<?php

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Support\LeaveSupport;
use App\Models\SystemConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('leave list page successfully loads', function () {
    Leave::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/leaves');

    $response->assertOk()
        ->assertSee('id="edit_leave_id"', false)
        ->assertViewHas('leaves', function ($leaves): bool {
            return $leaves->count() === 10;
        });
});

test('Leave can be created', function () {
    $leaveData = [
        'employee_id' => Employee::factory()->create()->id,
        'leave_type' => 'Annual',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'reason' => 'Vacation',
        'status' => 'pending',
    ];

    $response = $this->actingAs($this->user)->post('/leaves', $leaveData);

    $response->assertStatus(302);
    $response->assertRedirect('/leaves');
    $this->assertDatabaseCount('leaves', 1);
    $this->assertDatabaseHas('leaves', [
        'employee_id' => $leaveData['employee_id'],
        'leave_type' => 'Annual',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'reason' => 'Vacation',
        'status' => 'pending',
    ]);
});

test('Leave can be Updated', function () {
    $employee = Employee::factory()->create();
    $leave = Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'reason' => 'Vacation',
        'status' => 'pending',
    ]);
    $updateData = [
        'employee_id' => $employee->id,
        'leave_type' => 'Medical',
        'start_date' => '2024-02-01',
        'end_date' => '2024-02-05',
        'reason' => 'Sick leave',
        'status' => 'approved',
    ];

    $response = $this->actingAs($this->user)->put("/leaves/{$leave->id}", $updateData);

    $response->assertStatus(302);
    $response->assertRedirect('/leaves');
    $this->assertDatabaseHas('leaves', [
        'id' => $leave->id,
        'employee_id' => $employee->id,
        'leave_type' => 'Medical',
        'start_date' => '2024-02-01',
        'end_date' => '2024-02-05',
        'reason' => 'Sick leave',
        'status' => 'approved',
    ]);
});

test('Leave can be Deleted', function () {
    $employee = Employee::factory()->create();
    $leave = Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'reason' => 'Vacation',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)->delete("/leaves/{$leave->id}");

    $response->assertStatus(302);
    $response->assertRedirect('/leaves');
    $this->assertDatabaseMissing('leaves', [
        'id' => $leave->id,
    ]);
});

test('an employee cannot have multiple leave types on the same day', function () {
    $employee = Employee::factory()->create();
    Leave::factory()->create([
        'employee_id' => $employee->id,
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-01',
        'leave_type' => 'Annual',
    ]);
    $leave2 = Leave::factory()->make([
        'employee_id' => $employee->id,
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-01',
        'leave_type' => 'Medical',
    ]);

    $response = $this->actingAs($this->user)->post('/leaves', $leave2->toArray());

    $response->assertStatus(302);
    $response->assertSessionHasErrors('leave_type');
    $this->assertDatabaseCount('leaves', 1);
});

test('leave page loads leave counts from system configuration', function () {
    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => [
            'Annual' => 14,
            'Medical' => 7,
            'casual' => 5,
        ]]
    );

    $response = $this->actingAs($this->user)->get('/leaves');

    $response->assertOk()
        ->assertViewHas('leaveCounts', function ($leaveCounts): bool {
            return $leaveCounts['Annual'] === 14
                && $leaveCounts['Medical'] === 7
                && $leaveCounts['casual'] === 5;
        });
});

test('employee remaining leave decreases after taking leave', function () {
    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => [
            'Annual' => 14,
            'Medical' => 7,
            'casual' => 5,
        ],
        ]
    );
    $employee = Employee::factory()->create();
    Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-01',
        'status' => 'approved',
    ]);

    $remainingDays = LeaveSupport::getRemainingDays(
        $employee->id,
        'Annual',
        2026
    );

    expect($remainingDays)->toBe(13);
    $this->assertDatabaseHas('leaves', [
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-01',
        'status' => 'approved',
    ]);
});

test('employee can not get the leave, when he get the full leave', function () {
    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => [
            'Annual' => 0,
            'Medical' => 1,
            'casual' => 0,
        ],
        ]
    );
    $employee = Employee::factory()->create();
    Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type' => 'Medical',
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-01',
        'status' => 'approved',
    ]);

    $remainingDays = LeaveSupport::getRemainingDays(
        $employee->id,
        'Medical',
        2026
    );

    expect($remainingDays)->toBe(0);
});

test('employee cannot request leave after using the full allowance', function () {
    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => [
            'Annual' => 0,
            'Medical' => 1,
            'casual' => 0,
        ],
        ]
    );
    $employee = Employee::factory()->create();
    Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type' => 'Medical',
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-01',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($this->user)->post('/leaves', [
        'employee_id' => $employee->id,
        'leave_type' => 'Medical',
        'start_date' => '2026-10-02',
        'end_date' => '2026-10-02',
        'reason' => 'Follow-up appointment',
        'status' => 'pending',
    ]);

    $response->assertSessionHasErrors('leave_type');
    $this->assertDatabaseCount('leaves', 1);
});

test('employee remaining leave decreases by requested number of days', function () {
    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => [
            'Annual' => 14,
            'Medical' => 7,
            'casual' => 5,
        ]]
    );

    $employee = Employee::factory()->create();

    Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-03',
        'status' => 'approved',
    ]);

    expect(
        LeaveSupport::getRemainingDays($employee->id, 'Annual', 2026)
    )->toBe(11);
});

test('leave balance endpoint returns the employee balance for the current year', function () {
    $this->travelTo('2026-10-08 12:00:00');

    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => [
            'Annual' => 14,
            'Medical' => 7,
            'casual' => 5,
        ]]
    );
    $employee = Employee::factory()->create();
    Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-03',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($this->user)->getJson(route('leaves.balance', [
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
    ]));

    $response->assertOk()->assertJson([
        'success' => true,
        'balance' => [
            'max_days' => 14,
            'used_days' => 3,
            'remaining_days' => 11,
            'year' => 2026,
        ],
    ]);
});

test('leave balance endpoint rejects unsupported leave types', function () {
    $employee = Employee::factory()->create();

    $response = $this->actingAs($this->user)->getJson(route('leaves.balance', [
        'employee_id' => $employee->id,
        'leave_type' => 'Unpaid',
    ]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('leave_type');
});

test('leave balance endpoint returns 422 for an unknown employee', function () {
    $response = $this->actingAs($this->user)->getJson(route('leaves.balance', [
        'employee_id' => 999999,
        'leave_type' => 'Annual',
    ]));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('employee_id');
});

test('leave can be created without an end date and defaults to start date', function () {
    $employee = Employee::factory()->create();

    $response = $this->actingAs($this->user)->post(route('leaves.store'), [
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2026-10-15',
        'reason' => 'Single day leave',
        'status' => 'pending',
    ]);

    $response->assertRedirect(route('leaves.index'));
    $this->assertDatabaseHas('leaves', [
        'employee_id' => $employee->id,
        'leave_type' => 'Annual',
        'start_date' => '2026-10-15',
        'end_date' => '2026-10-15',
        'reason' => 'Single day leave',
    ]);
});
