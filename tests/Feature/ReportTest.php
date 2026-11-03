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

test('attendance report page can show report data', function () {
    $employee = Employee::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);
    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'date' => '2026-10-15',
        'in_time' => '08:00:00',
        'out_time' => '17:00:00',
    ]);
    $date = '2026-10-15';

    $response = $this->actingAs($this->user)->post('/dailyReport', ['date' => $date]);

    $response->assertOk();
    $response->assertSeeText([
        'Jane',
        'Doe',
        '2026-10-15',
        '08:00:00',
        '17:00:00',
        '9',
    ]);
});





