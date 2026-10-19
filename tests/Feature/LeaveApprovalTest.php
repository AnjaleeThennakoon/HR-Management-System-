<?php

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveApproval;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('Leave approval list loads successfully', function () {
    LeaveApproval::factory()->count(10)->create([
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)->get(route('leave-approval.index'));

    $response->assertOk()
        ->assertSeeText('Pending')
        ->assertViewHas('leaves', function ($leaves) {
            return $leaves->count() === 10;
        });
});

test('leave approval can be created', function () {
    $employee = Employee::factory()->create();
    $leave = Leave::factory()->create([
        'employee_id' => $employee->id,
    ]);
    $leaveApprovalData = [
        'leave_id' => $leave->id,
        'employee_id' => $employee->id,
        'status' => 'pending',
        'remarks' => 'first Remark',
        'approved_at' => '2026-10-12',
    ];

    $response = $this->actingAs($this->user)
        ->post(route('leave-approval.store'), $leaveApprovalData);

    $response->assertStatus(302);
    $response->assertRedirect(route('leave-approval.index'));
    $this->assertDatabaseHas('leave_approvals', [
        'leave_id' => $leave->id,
        'employee_id' => $employee->id,
        'status' => 'pending',
        'remarks' => 'first Remark',
    ]);
});

test('leave approval can be updated', function () {
    $employee = Employee::factory()->create();
    $leave = Leave::factory()->create([
        'employee_id' => $employee->id,
    ]);
    $leaveApproval = LeaveApproval::factory()->create([
        'leave_id' => $leave->id,
        'employee_id' => $employee->id,
        'status' => 'pending',
        'remarks' => 'first Remark',
        'approved_at' => '2026-10-12',
    ]);
    $updateLeaveApprovalData = [
        'leave_id' => $leave->id,
        'employee_id' => $employee->id,
        'status' => 'approved',
        'remarks' => 'first Remark',
        'approved_at' => '2026-10-13',
    ];

    $response = $this->actingAs($this->user)->put("/leavesApproval/{$leaveApproval->id}", $updateLeaveApprovalData);


    $response->assertStatus(302);
    $response->assertRedirect(route('leave-approval.index'));
    $this->assertDatabaseHas('leave_approvals', [
        'id' => $leaveApproval->id,
        'leave_id' => $leave->id,
        'employee_id' => $employee->id,
        'status' => 'approved',
        'remarks' => 'first Remark',
        'approved_at' => '2026-10-13',
    ]);
});



test('leave approval can be deleted', function () {
    $employee = Employee::factory()->create();
    $leave = Leave::factory()->create([
        'employee_id' => $employee->id,
    ]);
    $leaveApproval = LeaveApproval::factory()->create([
        'leave_id' => $leave->id,
        'employee_id' => $employee->id,
    ]);

    $response = $this->actingAs($this->user)
        ->delete(route('leave-approval.destroy', $leaveApproval->id));

    $response->assertRedirect(route('leave-approval.index'));
    $this->assertDatabaseMissing('leave_approvals', [
        'id' => $leaveApproval->id,
    ]);
});
