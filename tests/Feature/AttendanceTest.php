<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('attendance page loads successfully', function () {
    Attendance::factory()->count(10)->create();

    $response = $this->actingAs($this->user)->get('/attendance');

    $response->assertOk()
        ->assertViewHas('attendances', function ($attendances): bool {
            return $attendances->count() === 10;
        });
});

test('an attendance record can be created', function () {
    $attendance = Attendance::factory()->make([
    'date' => '2024-06-01',
    'in_time' => '09:00',
    'out_time' => '17:00',
    'employee_id' => Employee::factory()->create()->id,
    ]);

    $response = $this->actingAs($this->user)->post('/attendance', $attendance->toArray());

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseCount('attendances', 1);
    $this->assertDatabaseHas('attendances',[
        'employee_id' => $attendance->employee_id,
        'date' => '2024-06-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);
});

test('an attendance record can be updated', function () {
   $attendance = Attendance::factory()->create([
       'employee_id' => Employee::factory()->create()->id,
       'date' => '2024-06-01',
       'in_time' => '09:00',
       'out_time' => '17:00',
   ]);
   $updatedAttendance = [
       'employee_id' => $attendance->employee_id,
       'date' => '2024-06-02',
       'in_time' => '10:00',
       'out_time' => '18:00',
   ];

    $response = $this->actingAs($this->user)->put("/attendance/{$attendance->id}", $updatedAttendance);

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseHas('attendances', [
        'id' => $attendance->id,
        'employee_id' => $attendance->employee_id,
        'date' => '2024-06-02',
        'in_time' => '10:00',
        'out_time' => '18:00',
    ]);

});

test('an attendance record can be deleted', function () {
    $attendance = Attendance::factory()->create([
        'employee_id' => Employee::factory()->create()->id,
        'date' => '2024-06-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);

    $response = $this->actingAs($this->user)->delete("/attendance/{$attendance->id}");

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseMissing('attendances', [
        'id' => $attendance->id,
    ]);
});
