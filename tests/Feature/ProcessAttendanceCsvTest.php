<?php

use App\Jobs\ProcessAttendanceCsv;
use App\Models\AttendanceImport;
use App\Models\Employee;
use App\Models\User;
use App\UseCases\Attendance\UploadAttendanceInteractors;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('queued CSV processing reads files from the local disk root', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $employee = Employee::factory()->create();
    $attendanceImport = AttendanceImport::query()->create([
        'user_id' => $user->id,
        'file_path' => 'temp/attendance.csv',
    ]);
    Storage::disk('local')->put(
        $attendanceImport->file_path,
        "employee_id,date,in_time,out_time\n{$employee->employee_id},2024-01-01,08:00,17:30\n"
    );

    $job = new ProcessAttendanceCsv($attendanceImport->id, $attendanceImport->file_path, $user->id);
    $job->handle(app(UploadAttendanceInteractors::class));

    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employee->id,
        'date' => '2024-01-01',
        'in_time' => '08:00:00',
        'out_time' => '17:30:00',
    ]);
    $this->assertDatabaseHas('attendance_imports', [
        'id' => $attendanceImport->id,
        'status' => 'completed',
        'summary' => '0 of 1 rows failed. 1 imported successfully.',
    ]);
    Storage::disk('local')->assertMissing($attendanceImport->file_path);
});

test('queued CSV processing logs invalid rows without failing the job', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $employee = Employee::factory()->create();
    $attendanceImport = AttendanceImport::query()->create([
        'user_id' => $user->id,
        'file_path' => 'temp/invalid.csv',
    ]);

    Storage::disk('local')->put(
        $attendanceImport->file_path,
        "employee_id,date,in_time,out_time\n
        {$employee->employee_id},2024-01-01,08:00,17:30\n
        INVALID_EMP,2024-01-01,08:00,17:30\n"
    );

    $job = new ProcessAttendanceCsv($attendanceImport->id, $attendanceImport->file_path, $user->id);

    Log::shouldReceive('warning')
        ->once()
        ->with(
            'Attendance CSV import completed with invalid rows.',
            Mockery::on(fn (array $context): bool => $context['user_id'] === $user->id
                && isset($context['errors']['row_3']))
        );

    $job->handle(app(UploadAttendanceInteractors::class));

    $this->assertDatabaseCount('attendances', 1);
    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employee->id,
        'date' => '2024-01-01',
    ]);
    $this->assertDatabaseHas('attendance_imports', [
        'id' => $attendanceImport->id,
        'status' => 'completed',
        'summary' => '1 of 2 rows failed. 1 imported successfully.',
    ]);
    expect($attendanceImport->fresh()->rows[1]['errors'][0])
        ->toContain('does not exist');
});

test('attendance upload redirects to a queued import and dispatches its job', function () {
    Storage::fake('local');
    Queue::fake([ProcessAttendanceCsv::class]);
    $user = User::factory()->create();
    $file = UploadedFile::fake()->createWithContent(
        'attendance.csv',
        "employee_id,date,in_time,out_time\n"
    );

    $response = $this->actingAs($user)->post('/attendance/upload', [
        'csv_file' => $file,
    ]);

    $attendanceImport = AttendanceImport::query()->firstOrFail();
    $response->assertRedirect(route('attendance.index'));
    $response->assertSessionHas('attendance_import_id', $attendanceImport->id);
    $this->assertDatabaseHas('attendance_imports', [
        'id' => $attendanceImport->id,
        'user_id' => $user->id,
        'status' => 'queued',
    ]);
    Queue::assertPushed(
        ProcessAttendanceCsv::class,
        fn (ProcessAttendanceCsv $job): bool => $job->attendanceImportId === $attendanceImport->id
            && $job->filePath === $attendanceImport->file_path
    );
});

test('only the owner can check an attendance import status', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $attendanceImport = AttendanceImport::query()->create([
        'user_id' => $owner->id,
        'file_path' => 'temp/private.csv',
    ]);

    $this->actingAs($owner)
        ->getJson(route('attendance.imports.status', $attendanceImport->id))
        ->assertOk()
        ->assertJsonPath('status', 'queued');

    $this->actingAs($otherUser)
        ->getJson(route('attendance.imports.status', $attendanceImport->id))
        ->assertNotFound();
});

test('queued attendance imports show progress polling in the page', function () {
    $user = User::factory()->create();
    $attendanceImport = AttendanceImport::query()->create([
        'user_id' => $user->id,
        'file_path' => 'temp/queued.csv',
    ]);

    $this->actingAs($user)
        ->get(route('attendance.index', ['import' => $attendanceImport->id]))
        ->assertSee('data-status-url="'.route('attendance.imports.status', $attendanceImport->id).'"', false)
        ->assertSee('This page will update automatically when it finishes.')
        ->assertSee('window.location.reload()');
});

test('failed attendance imports display a useful status message', function () {
    $user = User::factory()->create();
    $attendanceImport = AttendanceImport::query()->create([
        'user_id' => $user->id,
        'file_path' => 'temp/missing.csv',
        'status' => 'failed',
        'error_message' => 'CSV processing failed. Please check the file and try again.',
    ]);

    $this->actingAs($user)
        ->get(route('attendance.index', ['import' => $attendanceImport->id]))
        ->assertSee('CSV processing failed. Please check the file and try again.');
});

test('completed imports show row errors without requiring a details click', function () {
    $user = User::factory()->create();
    $attendanceImport = AttendanceImport::query()->create([
        'user_id' => $user->id,
        'file_path' => 'temp/invalid.csv',
        'status' => 'completed',
        'summary' => '1 of 1 rows failed. 0 imported successfully.',
        'rows' => [
            [
                'row_number' => 2,
                'employee_id' => 'EMP99999',
                'status' => 'fail',
                'errors' => ['Employee ID does not exist.'],
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('attendance.index', ['import' => $attendanceImport->id]))
        ->assertSee('Employee ID does not exist.')
        ->assertDontSee('id="errorDetailsList" class="hidden', false);
});

test('CSV file validation errors appear on the attendance page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from('/attendance')
        ->followingRedirects()
        ->post('/attendance/upload')
        ->assertSee('The csv file field is required.');
});

test('permanently failed CSV jobs update their import status', function () {
    $user = User::factory()->create();
    $attendanceImport = AttendanceImport::query()->create([
        'user_id' => $user->id,
        'file_path' => 'temp/missing.csv',
    ]);
    $job = new ProcessAttendanceCsv($attendanceImport->id, $attendanceImport->file_path, $user->id);

    $job->failed(new RuntimeException('File missing.'));

    $this->assertDatabaseHas('attendance_imports', [
        'id' => $attendanceImport->id,
        'status' => 'failed',
        'error_message' => 'CSV processing failed. Please check the file and try again.',
    ]);
});
