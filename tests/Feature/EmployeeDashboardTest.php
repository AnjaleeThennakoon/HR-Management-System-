<?php

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\SystemConfiguration;
use App\Models\User;
use Database\Seeders\EmployeeRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Role::create(['name' => 'admin']);
    $this->seed(EmployeeRoleSeeder::class);

    SystemConfiguration::updateOrCreate(
        ['key' => 'leave'],
        ['value' => [
            'Annual' => 14,
            'Medical' => 7,
            'casual' => 7,
        ]]
    );

    $this->department = Department::create(['name' => 'Engineering']);
    $this->designation = Designation::create([
        'name' => 'Software Engineer',
        'level' => 2,
    ]);

    $this->employeeRecord = Employee::create([
        'employee_id' => 'EMP100',
        'department_id' => $this->department->id,
        'designation_id' => $this->designation->id,
        'first_name' => 'Alice',
        'last_name' => 'Smith',
        'date_of_birth' => '1996-04-12',
        'gender' => 'female',
        'nic' => '961031234V',
        'phone' => '0779998888',
        'address' => '456 Colombo Road',
        'salary' => 120000.00,
        'shift_in' => '09:00:00',
        'shift_out' => '17:00:00',
    ]);

    $this->employeeUser = User::create([
        'email' => 'alice@hr.com',
        'password' => bcrypt('password123'),
        'role' => 'employee',
        'employee_id' => $this->employeeRecord->employee_id,
    ]);
    $this->employeeUser->assignRole('employee');
});

test('employee dashboard displays profile, department, and designation', function () {
    $response = $this->actingAs($this->employeeUser)
        ->get(route('employee.dashboard'));

    $response->assertOk()
        ->assertSee('Alice Smith')
        ->assertSee('EMP100')
        ->assertSee('Engineering')
        ->assertSee('Software Engineer')
        ->assertSee('alice@hr.com')
        ->assertSee('0779998888');
});

test('employee dashboard displays attendance records and current month stats', function () {
    Attendance::create([
        'employee_id' => $this->employeeRecord->id,
        'date' => now()->toDateString(),
        'in_time' => '08:50:00',
        'out_time' => '17:05:00',
        'late_minutes' => 0,
        'ot_minutes' => 5,
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->get(route('employee.dashboard'));

    $response->assertOk()
        ->assertSee('Attendance Log')
        ->assertSee('Present Days')
        ->assertSee('08:50 AM');
});

test('employee dashboard displays company holidays', function () {
    Holiday::create([
        'name' => 'National Day',
        'date' => now()->addDays(10)->toDateString(),
        'type' => 'Public',
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->get(route('employee.dashboard'));

    $response->assertOk()
        ->assertSee('Company and Public Holidays')
        ->assertSee('National Day');
});

test('employee can apply for leave from dashboard', function () {
    $response = $this->actingAs($this->employeeUser)
        ->post(route('leaves.store'), [
            'employee_id' => $this->employeeRecord->id,
            'leave_type' => 'Annual',
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'reason' => 'Rest and recovery',
        ]);

    $response->assertRedirect(route('employee.dashboard'))
        ->assertSessionHas('success', 'Leave request submitted successfully.');

    $this->assertDatabaseHas('leaves', [
        'employee_id' => $this->employeeRecord->id,
        'leave_type' => 'Annual',
        'reason' => 'Rest and recovery',
        'status' => 'pending',
    ]);
});

test('employee cannot apply for leave on behalf of another employee', function () {
    $otherEmployee = Employee::factory()->create();

    $response = $this->actingAs($this->employeeUser)
        ->post(route('leaves.store'), [
            'employee_id' => $otherEmployee->id,
            'leave_type' => 'Annual',
            'start_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
            'reason' => 'Attempting leave for someone else',
        ]);

    $response->assertForbidden();
});

test('employee can edit their pending leave request', function () {
    $leave = Leave::create([
        'employee_id' => $this->employeeRecord->id,
        'leave_type' => 'Annual',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
        'reason' => 'Original reason',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->put(route('leaves.update', $leave->id), [
            'employee_id' => $this->employeeRecord->id,
            'leave_type' => 'Medical',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'reason' => 'Updated medical reason',
        ]);

    $response->assertRedirect(route('employee.dashboard'))
        ->assertSessionHas('success', 'Leave request updated successfully.');

    $this->assertDatabaseHas('leaves', [
        'id' => $leave->id,
        'leave_type' => 'Medical',
        'reason' => 'Updated medical reason',
        'status' => 'pending',
    ]);
});

test('employee cannot edit another employees leave request', function () {
    $otherEmployee = Employee::factory()->create();
    $leave = Leave::create([
        'employee_id' => $otherEmployee->id,
        'leave_type' => 'Annual',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
        'reason' => 'Other employee leave',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->put(route('leaves.update', $leave->id), [
            'employee_id' => $otherEmployee->id,
            'leave_type' => 'Annual',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'reason' => 'Hacked reason',
        ]);

    $response->assertForbidden();
});

test('employee cannot edit an approved or rejected leave request', function () {
    $leave = Leave::create([
        'employee_id' => $this->employeeRecord->id,
        'leave_type' => 'Annual',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
        'reason' => 'Approved leave',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->put(route('leaves.update', $leave->id), [
            'employee_id' => $this->employeeRecord->id,
            'leave_type' => 'Annual',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'reason' => 'Trying to change approved',
        ]);

    $response->assertForbidden();
});

test('employee can delete their pending leave request', function () {
    $leave = Leave::create([
        'employee_id' => $this->employeeRecord->id,
        'leave_type' => 'Annual',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
        'reason' => 'To be deleted',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->delete(route('leaves.destroy', $leave->id));

    $response->assertRedirect(route('employee.dashboard'))
        ->assertSessionHas('success', 'Leave request deleted successfully.');

    $this->assertDatabaseMissing('leaves', [
        'id' => $leave->id,
    ]);
});

test('employee cannot delete another employees leave request', function () {
    $otherEmployee = Employee::factory()->create();
    $leave = Leave::create([
        'employee_id' => $otherEmployee->id,
        'leave_type' => 'Annual',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
        'reason' => 'Other employee leave',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->delete(route('leaves.destroy', $leave->id));

    $response->assertForbidden();
});

test('employee cannot delete approved leave request', function () {
    $leave = Leave::create([
        'employee_id' => $this->employeeRecord->id,
        'leave_type' => 'Annual',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
        'reason' => 'Approved leave',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->delete(route('leaves.destroy', $leave->id));

    $response->assertForbidden();
});

test('employee can view their payroll details and download payroll PDF', function () {
    $payroll = Payroll::create([
        'employee_id' => $this->employeeRecord->id,
        'month' => 10,
        'year' => 2026,
        'basic_salary' => 120000.00,
        'allowances' => 10000.00,
        'deductions' => 9600.00,
        'EPF' => 9600.00,
        'net_salary' => 120400.00,
    ]);

    $dashResponse = $this->actingAs($this->employeeUser)
        ->get(route('employee.dashboard'));

    $dashResponse->assertOk()
        ->assertSee('Payroll Records and Salary Statements')
        ->assertSee('Rs. 120,400.00')
        ->assertSee(route('payrolls.download', $payroll->id));

    $pdfResponse = $this->actingAs($this->employeeUser)
        ->get(route('payrolls.download', $payroll->id));

    $pdfResponse->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');

    expect($pdfResponse->getContent())->toContain('%PDF-1.4')
        ->toContain('HR SYSTEM - SALARY SLIP');
});

test('employee cannot download another employees payroll PDF', function () {
    $otherEmployee = Employee::factory()->create();
    $otherPayroll = Payroll::create([
        'employee_id' => $otherEmployee->id,
        'month' => 10,
        'year' => 2026,
        'basic_salary' => 150000.00,
        'allowances' => 10000.00,
        'deductions' => 12000.00,
        'EPF' => 12000.00,
        'net_salary' => 148000.00,
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->get(route('payrolls.download', $otherPayroll->id));

    $response->assertForbidden();
});

test('employee cannot see other employees data or admin-only controls', function () {
    $otherEmployee = Employee::factory()->create([
        'first_name' => 'SecretBoss',
        'last_name' => 'VIP',
    ]);

    $otherPayroll = Payroll::create([
        'employee_id' => $otherEmployee->id,
        'month' => 9,
        'year' => 2026,
        'basic_salary' => 999999.00,
        'allowances' => 0.00,
        'deductions' => 0.00,
        'EPF' => 0.00,
        'net_salary' => 999999.00,
    ]);

    $response = $this->actingAs($this->employeeUser)
        ->get(route('employee.dashboard'));

    $response->assertOk()
        ->assertDontSee('SecretBoss')
        ->assertDontSee('999999.00')
        ->assertDontSee('Process Monthly Payment')
        ->assertDontSee('Go to Attendance Finalization');
});
