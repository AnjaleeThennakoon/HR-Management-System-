<?php

use App\Models\Employee;
use App\Models\Leave;
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
