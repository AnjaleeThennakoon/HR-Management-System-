<?php

use App\Models\Attendance;
use App\Models\AttendanceImport;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\SystemConfiguration;
use App\Models\User;
use Carbon\Carbon;
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

test('CSV with 5 rows imports 5 attendances', function () {
    Storage::fake('local');

    $employees = Employee::factory()->count(5)->create()->toArray();

    $csvContent = "employee_id,date,in_time,out_time\n";
    foreach ($employees as $employee) {
        $csvContent .= "{$employee['employee_id']},2024-01-01,08:00,17:30\n";
    }

    $file = UploadedFile::fake()->createWithContent('attendance.csv', $csvContent);

    $response = $this->actingAs($this->user)
        ->post('/attendance/upload', ['csv_file' => $file]);

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseCount('attendances', 5);

    foreach ($employees as $employee) {
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee['id'],
            'date' => '2024-01-01',
            'in_time' => '08:00:00',
            'out_time' => '17:30:00',
        ]);
    }
});

test('CSV with invalid rows and shows errors and saves valid ones', function () {
    $employees = Employee::factory()->count(3)->create();
    $csvContent = "employee_id,date,in_time,out_time\n";
    $csvContent .= "{$employees[0]->employee_id},2024-01-01,08:00,17:30\n";
    $csvContent .= "{$employees[1]->employee_id},2024-01-01,08:00\n";
    $csvContent .= "EMP99999,2024-01-01,08:00,17:30\n";
    $csvContent .= "{$employees[2]->employee_id},2024-01-01,08:00,17:30\n";
    $csvContent .= "EMP88888,2024-01-01\n";
    $file = UploadedFile::fake()->createWithContent('attendance.csv', $csvContent);

    $response = $this->actingAs($this->user)
        ->from('/attendance')
        ->post('/attendance/upload', ['csv_file' => $file]);

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseCount('attendances', 2);
    $attendanceImport = AttendanceImport::query()->findOrFail(
        session('attendance_import_id')
    );

    expect($attendanceImport->status)->toBe('completed')
        ->and($attendanceImport->summary)->toBe('3 of 5 rows failed. 2 imported successfully.');

    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employees[0]->id,
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
    $this->assertDatabaseMissing('attendances', ['employee_id' => $employees[1]->id]);
    expect($attendanceImport->rows[1]['row_number'])->toBe(3)
        ->and($attendanceImport->rows[1]['errors'][0])->toContain('Invalid column count');
    $this->assertDatabaseMissing('attendances', ['employee_id' => 99999]);
    expect($attendanceImport->rows[2]['row_number'])->toBe(4)
        ->and($attendanceImport->rows[2]['errors'][0])->toContain('does not exist');
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employees[2]->id,
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
    $this->assertDatabaseMissing('attendances', ['employee_id' => 88888]);
    expect($attendanceImport->rows[4]['row_number'])->toBe(6)
        ->and($attendanceImport->rows[4]['errors'][0])->toContain('Invalid column count');
    $this->get('/attendance')->assertOk();
});

test('CSV with duplicate attendance shows error', function () {
    $employee = Employee::factory()->create();
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2024-01-01',
    ]);
    $csvContent = "employee_id,date,in_time,out_time\n";
    $csvContent .= "{$employee->employee_id},2024-01-01,08:00,17:30\n";

    $file = UploadedFile::fake()->createWithContent('attendance.csv', $csvContent);

    $response = $this->actingAs($this->user)
        ->from('/attendance')
        ->post('/attendance/upload', ['csv_file' => $file]);
    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseCount('attendances', 1);

    $attendanceImport = AttendanceImport::query()->findOrFail(
        session('attendance_import_id')
    );

    expect($attendanceImport->status)->toBe('completed')
        ->and($attendanceImport->rows[0]['status'])->toBe('fail')
        ->and($attendanceImport->rows[0]['errors'][0])->toContain('already exists');
});

test('non-CSV file is rejected', function () {
    $file = UploadedFile::fake()->create('attendance.txt', 100);

    $response = $this->actingAs($this->user)
        ->post('/attendance/upload', ['csv_file' => $file]);

    $response->assertSessionHasErrors('csv_file');
});

test('CSV import shows summary with row details', function () {
    $employees = Employee::factory()->count(2)->create();
    $csvContent = "employee_id,date,in_time,out_time\n";
    $csvContent .= "{$employees[0]->employee_id},2024-01-01,08:00,17:30\n";
    $csvContent .= "INVALID,2024-01-01,08:00,17:30\n";
    $file = UploadedFile::fake()->createWithContent('attendance.csv', $csvContent);

    $response = $this->actingAs($this->user)
        ->from('/attendance')
        ->post('/attendance/upload', ['csv_file' => $file]);

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $attendanceImport = AttendanceImport::query()->findOrFail(
        session('attendance_import_id')
    );
    expect($attendanceImport->status)->toBe('completed')
        ->and($attendanceImport->summary)->toBe('1 of 2 rows failed. 1 imported successfully.');
    $importRows = $attendanceImport->rows;
    expect($importRows)->toHaveCount(2);
    $row1 = $importRows[0];
    expect($row1['row_number'])->toBe(2)
        ->and($row1['employee_id'])->toBe($employees[0]->employee_id)
        ->and($row1['status'])->toBe('success')
        ->and($row1['errors'])->toBeEmpty();
    $row2 = $importRows[1];
    expect($row2['row_number'])->toBe(3)
        ->and($row2['employee_id'])->toBe('INVALID')
        ->and($row2['status'])->toBe('fail')
        ->and($row2['errors'])->not->toBeEmpty()
        ->and($row2['errors'][0])->toContain('does not exist');
});

test('CSV with duplicate rows show error', function () {
    $employee = Employee::factory()->create();
    $csvContent = "employee_id,date,in_time,out_time\n";
    $csvContent .= "{$employee->employee_id},2026-10-07,08:30,17:30\n";
    $csvContent .= "{$employee->employee_id},2026-10-07,08:30,17:30\n";
    $file = UploadedFile::fake()->createWithContent('attendance.csv', $csvContent);

    $response = $this->actingAs($this->user)
        ->from('/attendance')
        ->post('/attendance/upload', ['csv_file' => $file]);

    $response->assertStatus(302);
    $response->assertRedirect('/attendance');
    $this->assertDatabaseCount('attendances', 1);

    $attendanceImport = AttendanceImport::query()->findOrFail(
        session('attendance_import_id')
    );
    $duplicateRowErrors = $attendanceImport->rows[1]['errors'];

    expect($attendanceImport->status)->toBe('completed')
        ->and($attendanceImport->rows[1]['row_number'])->toBe(3)
        ->and($duplicateRowErrors[0])->toContain('already exists')
        ->and($duplicateRowErrors[0])->not->toContain('SQLSTATE')
        ->and($duplicateRowErrors[0])->not->toContain('Integrity constraint violation');
});

test('attendance cannot be created for a nonexistent employee', function () {
    $attendanceData = [
        'employee_id' => 999999,
        'date' => '2026-10-15',
        'in_time' => '08:00',
        'out_time' => '17:00',
    ];

    $response = $this->actingAs($this->user)
        ->post('/attendance', $attendanceData);

    $response->assertSessionHasErrors('employee_id');

    $this->assertDatabaseCount('attendances', 0);
});

test('attendance cannot be created with an invalid date', function () {
    $employee = Employee::factory()->create();
    $attendanceData = [
        'employee_id' => $employee->id,
        'date' => 'invalid-date',
        'in_time' => '08:00',
        'out_time' => '17:00',
    ];

    $response = $this->actingAs($this->user)->post('/attendance', $attendanceData);

    $response->assertSessionHasErrors('date');
    $this->assertDatabaseCount('attendances', 0);
});

test('attendance cannot be updated to a duplicate employee and date', function () {
    $employee = Employee::factory()->create();
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
        'in_time' => '08:00',
        'out_time' => '17:00',
    ]);
    $attendanceToUpdate = Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-16',
        'in_time' => '09:00',
        'out_time' => '18:00',
    ]);
    $attendanceData = [
        [
            'employee_id' => $employee->id,
            'date' => '2026-10-15',
            'in_time' => '09:00',
            'out_time' => '18:00',
        ],
    ];

    $response = $this->actingAs($this->user)->put("/attendance/{$attendanceToUpdate->id}", $attendanceData);

    $response->assertSessionHasErrors('employee_id');
    $this->assertDatabaseHas('attendances', [
        'id' => $attendanceToUpdate->id,
        'date' => '2026-10-16',
    ]);
    $this->assertDatabaseCount('attendances', 2);
});

test('monthly report view does not render attendance rows from other months', function () {
    $employee = Employee::factory()->create();
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
    ]);
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-11-15',
    ]);
    $employeeData = [
        'month' => 10,
        'year' => 2026,
        'employee_id' => $employee->id,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $employeeData));

    $response->assertOk()
        ->assertSee('data-date="2026-10-15"', false)
        ->assertDontSee('data-date="2026-11-15"', false);
});

test('saving attendance from the report page updates existing records', function () {
    $employee = Employee::factory()->create();
    $attendance = Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
        'in_time' => '08:00:00',
        'out_time' => null,
    ]);
    $employeeData = [
        '_method' => 'PUT',
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
        'in_time' => '08:00',
        'out_time' => '17:00',
    ];

    $response = $this->actingAs($this->user)->postJson("/attendance/{$attendance->id}", $employeeData);

    $response->assertOk();
    $this->assertDatabaseHas('attendances', [
        'id' => $attendance->id,
        'out_time' => '17:00:00',
    ]);
});

test('monthly report loads with employee, month and year', function () {
    $employee = Employee::factory()->create();
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
        'in_time' => '08:00',
        'out_time' => '17:00',
    ]);
    $attendanceData = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $attendanceData));

    $response->assertOk()
        ->assertViewHas('employee_id')
        ->assertViewHas('month', 10)
        ->assertViewHas('year', 2026)
        ->assertViewHas('attendances', function ($attendances) {
            $first = $attendances->first();

            return $attendances->count() === 1
                && ($first->date instanceof Carbon
                    ? $first->date->format('Y-m-d')
                    : $first->date) === '2026-10-15';
        });
});

test('monthly report includes only attendances for the selected month', function () {
    $employee = Employee::factory()->create();
    foreach (range(1, 30) as $day) {
        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => Carbon::create(2026, 10, $day)->toDateString(),
            'in_time' => '09:00',
            'out_time' => '17:00',
        ]);
    }
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-11-15',
    ]);
    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $payload));

    $response->assertOk();
    $this->assertDatabaseCount('attendances', 31);
    $response->assertViewHas('attendances', function ($attendances) {
        $countIsCorrect = $attendances->count() === 30;
        $doesNotHaveNextMonth = ! $attendances->contains('date', '2026-11-15');

        $allDaysPresent = collect(range(1, 30))->every(function ($day) use ($attendances) {
            $dateStr = Carbon::create(2026, 10, $day)->toDateString();

            return $attendances->contains('date', $dateStr);
        });

        return $countIsCorrect && $allDaysPresent && $doesNotHaveNextMonth;
    });
});

test('monthly report shows employee shift times', function () {
    $employee = Employee::factory()->create([
        'shift_in' => '08:00',
        'shift_out' => '17:00',
    ]);
    $employeeData = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $employeeData));

    $response->assertOk()
        ->assertSee('08:00')
        ->assertSee('17:00');
});

test('it calculates late time and overtime correctly', function () {
    $employee = Employee::factory()->create([
        'shift_in' => '08:00',
        'shift_out' => '17:00',
    ]);
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
        'in_time' => '08:15',
        'out_time' => '17:30',
    ]);
    $employeeData = [
        'month' => '10',
        'year' => '2026',
        'employee_id' => $employee->id,
    ];

    $response = $this->actingAs($this->user)
        ->get(route('attendance.monthly-report', $employeeData));

    $response->assertOk();
    $response->assertViewHas('attendances', function ($attendances) {
        $attendance = $attendances->first();

        return $attendance->late_minutes === 15
            && $attendance->ot_minutes === 30;
    });
});

test('can show error for a non selecting employee', function () {
    $employeeData = [
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $employeeData));

    $response->assertStatus(302);
    $response->assertSessionHasErrors('employee_id');
});

test('monthly report shows total late and ot minutes', function () {
    $employee = Employee::factory()->create([
        'shift_in' => '08:00',
        'shift_out' => '17:00',
    ]);
    $attendance1 = Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
        'in_time' => '08:15',
        'out_time' => '17:30',
    ]);
    $attendance2 = Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-16',
        'in_time' => '08:10',
        'out_time' => '17:00',
    ]);
    $employeeData = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $employeeData));

    $response->assertOk()
        ->assertViewHas('totalLateMinutes', 25)
        ->assertViewHas('totalOtMinutes', 30);
    $this->assertDatabaseHas('attendances', [
        'id' => $attendance1->id,
        'late_minutes' => 15,
        'ot_minutes' => 30,
    ]);
    $this->assertDatabaseHas('attendances', [
        'id' => $attendance2->id,
        'late_minutes' => 10,
        'ot_minutes' => 0,
    ]);
});

test('it correctly calculates the total non-working days for the month', function () {
    $employee = Employee::factory()->create();
    foreach (range(1, 5) as $day) {
        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => Carbon::create(2026, 10, $day)->toDateString(),
            'in_time' => '09:00',
            'out_time' => '17:00',
        ]);
    }
    foreach (range(6, 10) as $day) {
        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => Carbon::create(2026, 10, $day)->toDateString(),
            'in_time' => null,
            'out_time' => null,
        ]);
    }
    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];
    $expectedNonWorkingDays = 19;

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $payload));

    $response->assertOk();
    $response->assertViewHas('nonWorkingDays', $expectedNonWorkingDays);
    $response->assertViewHas('attendances', function ($attendances) {
        return $attendances->count() === 10;
    });
});

test('empty count excludes weekends, holidays, and leaves', function () {
    $employee = Employee::factory()->create();
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-02',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);
    Holiday::create([
        'name' => 'Test Holiday',
        'date' => '2026-10-12',
        'type' => 'public',
    ]);
    Leave::create([
        'employee_id' => $employee->id,
        'start_date' => '2026-10-13',
        'end_date' => '2026-10-14',
        'leave_type' => 'casual',
        'reason' => 'Personal',
        'status' => 'approved',
    ]);
    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];
    $expectedNonWorkingDays = 17;

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $payload));

    $response->assertOk();
    $response->assertViewHas('nonWorkingDays', $expectedNonWorkingDays);
});

test('weekend attendance is not counted as a non-working day', function () {
    $employee = Employee::factory()->create();
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-03',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);
    $reportData = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];
    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $reportData));

    $response->assertOk();
    $response->assertViewHas('nonWorkingDays', 22);
});

test('add shift time for a weekend, after showing error in monthly report', function () {
    $employee = Employee::factory()->create([
        'shift_in' => '08:00',
        'shift_out' => '17:00',
    ]);
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-03',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);
    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $payload));

    $response->assertOk()
        ->assertSee('09:00')
        ->assertSee('17:00')
        ->assertSee('Cannot add time for the weekend days');
});

test('monthly report page displays mark as leave and delete row action buttons', function () {
    $employee = Employee::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
    $attendance = Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-01',
        'in_time' => '09:00',
        'out_time' => '17:00',
    ]);

    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $payload));

    $response->assertOk()
        ->assertSee('title="Mark as a leave"', false)
        ->assertSee('title="Delete row"', false)
        ->assertSee('id="markLeaveModal"', false);
});

test('it calculates no-pay days correctly after the leave period ends', function () {
    $employee = Employee::factory()->create();
    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => ['Annual' => 0, 'Medical' => 0, 'casual' => 0]]
    );

    Leave::factory()->create([
        'employee_id' => $employee->id,
        'start_date' => '2026-10-12',
        'end_date' => '2026-10-13',
        'leave_type' => 'casual',
        'reason' => 'Personal',
        'status' => 'approved',
    ]);
    foreach (range(1, 31) as $day) {
        $date = Carbon::create(2026, 10, $day);

        if ($date->isWeekend() || in_array($day, [12, 13, 14, 15], true)) {
            continue;
        }
        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
            'in_time' => '09:00',
            'out_time' => '17:00',
        ]);
    }
    $payload = [
        'employee_id' => $employee->id,
        'month' => 10,
        'year' => 2026,
    ];

    $response = $this->actingAs($this->user)->get(route('attendance.monthly-report', $payload));

    $response->assertOk();
    $response->assertViewHas('nonPayDays', 2);
});
