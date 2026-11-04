<?php

use App\Models\Attendance;
use App\Models\Employee;
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
            'status' => 'Present',
        ];
        $response->assertSeeText([
            $attendanceData['first_name'],
            $attendanceData['last_name'],
            $attendanceData['in_time'],
            $attendanceData['out_time'],
            $attendanceData['working_hours'],
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
    $presentEmployee = Attendance::factory()->create([
        'employee_id' => $presentEmployee->id,
        'date' => '2026-10-01',
        'in_time' => '08:10:00',
        'out_time' => '18:00:00',
        'late_minutes' => 10,
        'ot_minutes' => 120,
    ]);
    $presentEmployee = Attendance::factory()->create([
        'employee_id' => $presentEmployee->id,
        'date' => '2026-10-02',
        'in_time' => '08:05:00',
        'out_time' => '17:00:00',
        'late_minutes' => 5,
        'ot_minutes' => 0,
    ]);

    $response = $this->actingAs($this->user)->get(route('report.monthlyReport', ['month' => $month]));

    $response->assertOk();
    $response->assertSeeTextInOrder([
        'Total Present Days: 2',
        'Total Absent Days: 42',
        'Total Late Minutes: 15',
        'Total OT Minutes: 120',
        'Total Work Hours: 17',
    ]);
    $response->assertSeeTextInOrder(['APSARA', '2', '20', 'Present']);
    $response->assertSeeTextInOrder(['NIMESH', '0', '22', 'Absent']);
    $response->assertViewHas('report', function ($report) {
        return count($report) === 2;
    });
});
