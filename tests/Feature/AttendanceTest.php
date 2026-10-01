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
    'employee_id' => 'EMP001',
    'date' => '2024-06-01',
    'in_time' => '09:00',
    'out_time' => '17:00',
    ]);

    $response = $this->actingAs($this->user)->post('/attendance', $attendance->toArray());

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseCount('attendances', 1);
    $this->assertDatabaseHas('attendances',[
        'employee_id' => 'EMP001',
        'date' => '2024-06-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);
});
