<?php

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
     $leave = Leave::factory()->make([
         'id' => '1',
         'leave_type' => 'Annual',
         'start_date' => '2024-01-01',
         'end_date' => '2024-01-10',
         'reason' => 'Vacation',
         'status' => 'pending',
     ]);

     $response = $this->actingAs($this->user)->post('/leaves', $leave->toArray());

     $response->assertStatus(302);
     $response->assertRedirect('/leaves');
     $this->assertDatabaseCount('leaves', 1);
     $this->assertDatabaseHas('employees', [
         'leave_type' => 'Annual',
         'date' => '2024-01-01',
         'start_date' => '2024-01-01',
         'end_date' => '2024-01-10',
         'reason' => 'Vacation',
         'status' => 'pending',
         'employee_id' => $leave->employee_id,
         'designation_id' => $leave->designation_id,
     ]);
 });
