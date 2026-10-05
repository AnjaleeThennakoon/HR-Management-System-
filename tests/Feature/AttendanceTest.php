<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('attendance list loads successfully', function () {
    $employee = Employee::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);
    Attendance::factory()->for($employee)->count(10)->create();

    $response = $this->actingAs($this->user)->get('/attendance');

    $response->assertOk()
        ->assertSeeText('Jane Doe')
        ->assertViewHas('attendances', function ($attendances): bool {
            return $attendances->count() === 10;
        });
});

test('an attendance record can be created', function () {
    $employee = Employee::factory()->create();

    $attendancedata = [
        'date' => '2024-06-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
        'employee_id' => $employee->id,
    ];

    $response = $this->actingAs($this->user)->post('/attendance', $attendancedata);

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseCount('attendances', 1);
    $this->assertDatabaseHas('attendances', [
        'date' => '2024-06-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
        'employee_id' => $employee->id,
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

test('an attendance in time cannot be after the out time', function () {
    $attendance = Attendance::factory()->make([
        'employee_id' => Employee::factory()->create()->id,
        'date' => '2024-06-01',
        'in_time' => '18:00',
        'out_time' => '17:00',
    ]);

    $response = $this->actingAs($this->user)->post('/attendance', $attendance->toArray());

    $response->assertStatus(302);
    $response->assertSessionHasErrors('in_time');
    $this->assertDatabaseCount('attendances', 0);
});

test('an attendance  time can not be invalid format', function () {
    $attendance = Attendance::factory()->make([
        'employee_id' => Employee::factory()->create()->id,
        'date' => '2024-06-01',
        'in_time' => '08:30:00',
        'out_time' => '17:00',
    ]);

    $response = $this->actingAs($this->user)->post('/attendance', $attendance->toArray());

    $response->assertStatus(302);
    $response->assertSessionHasErrors('in_time');
    $this->assertDatabaseCount('attendances', 0);
});

test('an employee cannot have two attendances on the same date', function () {
    $employee = Employee::factory()->create();
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2024-06-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);
    $duplicateAttendance = [
        'employee_id' => $employee->id,
        'date' => '2024-06-01',
        'in_time' => '10:00',
        'out_time' => '18:00',
    ];

    $response = $this->actingAs($this->user)->post('/attendance', $duplicateAttendance);

    $response->assertStatus(302);
    $response->assertSessionHasErrors('employee_id');
    $this->assertDatabaseCount('attendances', 1);
});
test(/**
 * @throws JsonException
 */ 'CSV with 5 rows imports 5 attendance records', function () {
    Storage::fake('local');
    $employees = Employee::factory()->count(5)->create()->toArray();
    $csvContent = "employee_id,date,in_time,out_time\n";
    $csvContent .= "{$employees[0]['employee_id']},2024-01-01,08:00,17:30\n";
    $csvContent .= "{$employees[1]['employee_id']},2024-01-01,08:00,17:30\n";
    $csvContent .= "{$employees[2]['employee_id']},2024-01-01,08:00,17:30\n";
    $csvContent .= "{$employees[3]['employee_id']},2024-01-01,08:00,17:30\n";
    $csvContent .= "{$employees[4]['employee_id']},2024-01-01,08:00,17:30\n";
    $file = UploadedFile::fake()->createWithContent(
        'attendance.csv',
        $csvContent
    );
    $response = $this->actingAs($this->user)
        ->post('/attendance/upload', [
            'csv_file' => $file,
        ]);

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseCount('attendances', 5);
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employees[0]['id'],
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employees[1]['id'],
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employees[2]['id'],
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employees[3]['id'],
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employees[4]['id'],
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
});
