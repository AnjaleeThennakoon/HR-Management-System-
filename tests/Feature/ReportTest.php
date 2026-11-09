<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('report page loads successfully', function () {
    $response = $this->actingAs($this->user)->get(route('report.index'));

    $response->assertOk()
        ->assertViewIs('Report.ReportDashBord');
});

test('attendance report page shows all employees who attended or absent on the selected date', function () {
    $employees = Employee::factory()->count(5)->create();
    $attendedEmployees = $employees->take(3);
    foreach ($attendedEmployees as $index => $employee) {
        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => '2026-10-15',
            'in_time' => '08:00:00',
            'out_time' => '17:00:00',
            'late_minutes' => 15,
            'ot_minutes' => 30,
        ]);
    }
    $employee1 = Attendance::factory()->create([
        'employee_id' => $employees[3]->id,
        'date' => '2026-10-16',
        'in_time' => '09:00:00',
        'out_time' => '17:00:00',
    ]);
    $employee2 = Attendance::factory()->create([
        'employee_id' => $employees[4]->id,
        'date' => '2026-10-16',
        'in_time' => '09:00:00',
        'out_time' => '17:00:00',
    ]);
    $date = '2026-10-15';

    $response = $this->actingAs($this->user)->get("dailyReport/{$date}");

    $response->assertOk();
    foreach ($attendedEmployees as $employee) {
        $attendanceData = [
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'in_time' => '08:00:00',
            'out_time' => '17:00:00',
            'working_hours' => '9',
            'ot_minutes' => 30,
            'late_minutes' => 15,
            'status' => 'Present',
        ];
        $response->assertSeeText([
            $attendanceData['first_name'],
            $attendanceData['last_name'],
            $attendanceData['in_time'],
            $attendanceData['out_time'],
            $attendanceData['working_hours'],
            $attendanceData['ot_minutes'],
            $attendanceData['late_minutes'],
            $attendanceData['status'],
        ]);
    }
    $response->assertDontSeeText($employee1->first_name);
    $response->assertDontSeeText($employee2->first_name);
});

test('monthly report shows all employees for selected month including absent ones', function () {
    $employees = Employee::factory()->count(5)->create();
    foreach ($employees->take(3) as $employee) {
        Attendance::factory()->create([
            'employee_id' => $employee->id,
            'date' => '2026-10-15',
            'in_time' => '08:00:00',
            'out_time' => '17:00:00',
            'late_minutes' => 0,
            'ot_minutes' => 0,
        ]);
    }
    $month = '2026-10';

    $response = $this->actingAs($this->user)->get(route('report.monthlyReport', ['month' => $month]));

    $response->assertOk();
    $response->assertViewHas('report', fn ($report) => count($report) === 5);
    foreach ($employees as $employee) {
        $response->assertSeeText([
            $employee->first_name,
            $employee->last_name,
            $employee->employee_id,
        ]);
    }
});

test('monthly attendance report displays all employees with correct summary counts', function () {
    $month = '2026-10';
    $presentEmployee = Employee::factory()->create([
        'first_name' => 'APSARA',
        'last_name' => 'Perera',
    ]);
    $absentEmployee = Employee::factory()->create([
        'first_name' => 'NIMESH',
        'last_name' => 'Fernando',
    ]);
    Attendance::factory()->create([
        'employee_id' => $presentEmployee->id,
        'date' => '2026-10-01',
        'in_time' => '08:10:00',
        'out_time' => '18:00:00',
        'late_minutes' => 10,
        'ot_minutes' => 120,
    ]);
    Attendance::factory()->create([
        'employee_id' => $presentEmployee->id,
        'date' => '2026-10-02',
        'in_time' => '08:05:00',
        'out_time' => '17:00:00',
        'late_minutes' => 5,
        'ot_minutes' => 0,
    ]);

    $response = $this->actingAs($this->user)->get(route('report.monthlyReport', ['month' => $month]));

    $response->assertOk();

    $response->assertDontSeeText('Status');
    $response->assertSeeTextInOrder(['APSARA', '2', '20', '2.00', '15m']);
    $response->assertSeeTextInOrder(['NIMESH', '0', '22', '0.00', '0m']);
    $response->assertViewHas('report', function ($report) {
        return count($report) === 2;
    });
});

test('show employees with leaves in the selected month', function () {
    $employee1 = Employee::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
    $leave = Leave::factory()->create([
        'employee_id' => $employee1->id,
        'start_date' => '2026-10-10',
        'end_date' => '2026-10-15',
        'status' => 'approved',
        'reason' => 'Vacation',
    ]);
    $employee2 = Employee::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'fernando',
    ]);
    $leave = Leave::factory()->create([
        'employee_id' => $employee2->id,
        'start_date' => '2026-10-30',
        'end_date' => '2026-10-30',
        'status' => 'pending',
        'reason' => 'Personal',
    ]);
    $employee3 = Employee::factory()->create([
        'first_name' => 'Alice',
        'last_name' => 'Smith',
    ]);
    $leave = Leave::factory()->create([
        'employee_id' => $employee3->id,
        'start_date' => '2026-10-20',
        'end_date' => '2026-10-25',
        'status' => 'approved',
        'reason' => 'No Pay Leave',
    ]);

    $month = '2026-10';

    $response = $this->actingAs($this->user)->get(route('report.leaveReport', ['month' => $month]));

    $response->assertOk();
    $response->assertSeeTextInOrder(['2026-10-10', '2026-10-15', 'John', 'Doe', 'Vacation', 'approved']);
    $response->assertSeeTextInOrder(['2026-10-30', '2026-10-30', 'Jane', 'fernando', 'Personal', 'pending']);
    $response->assertSeeTextInOrder(['2026-10-20', '2026-10-25', 'Alice', 'Smith', 'No Pay Leave', 'approved']);
});

test('show leave report with double month', function () {
    $employee1 = Employee::factory()->create([
        'first_name' => 'John',
        'last_name'  => 'Doe',
    ]);
    Leave::factory()->create([
        'employee_id' => $employee1->id,
        'start_date'  => '2026-10-29',
        'end_date'    => '2026-11-02',
        'status'      => 'approved',
        'reason'      => 'Vacation',
    ]);

    $month = '2026-11';

    $response = $this->actingAs($this->user)->get(route('report.leaveReport', ['month' => $month]));

    $response->assertOk();
    $response->assertSeeTextInOrder([
        '2026-11-01',
        '2026-11-02',
        'John',
        'Doe',
        'Vacation',
        'approved',
    ]);
});
