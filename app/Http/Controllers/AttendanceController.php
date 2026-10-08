<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAttendanceCsv;
use App\Models\Attendance;
use App\Models\AttendanceImport;
use App\Models\Employee;
use App\UseCases\Attendance\DeleteAttendanceInteractors;
use App\UseCases\Attendance\ListAttendanceInteractors;
use App\UseCases\Attendance\Request\AttendanceCsvRequest;
use App\UseCases\Attendance\Request\AttendanceRequest;
use App\UseCases\Attendance\StoreAttendanceInteractors;
use App\UseCases\Attendance\UpdateAttendanceInteractors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(ListAttendanceInteractors $listAttendanceInteractors): View
    {
        $attendances = $listAttendanceInteractors->execute(
            request('search'),
            request('per_page')
        );
        $importId = request()->integer('import') ?: (int) session('attendance_import_id', 0);
        $attendanceImport = $importId > 0
            ? AttendanceImport::query()
                ->where('user_id', auth()->id())
                ->findOrFail($importId)
            : null;

        return view('Attendance.AttendanceDashbord', [
            'attendances' => $attendances,
            'employees' => Employee::all(),
            'attendanceImport' => $attendanceImport,
        ]);
    }

    public function importStatus(Request $request, int $id): JsonResponse
    {
        $attendanceImport = AttendanceImport::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'status' => $attendanceImport->status,
            'summary' => $attendanceImport->summary,
            'rows' => $attendanceImport->rows,
            'error_message' => $attendanceImport->error_message,
        ]);
    }

    public function store(AttendanceRequest $attendanceRequest, StoreAttendanceInteractors $storeAttendanceInteractors): RedirectResponse
    {
        $storeAttendanceInteractors->execute($attendanceRequest->validated());

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully created.');
    }

    public function update(string $id, AttendanceRequest $attendanceRequest, UpdateAttendanceInteractors $updateAttendanceInteractors): RedirectResponse
    {
        $attendance = Attendance::findOrFail($id);
        $updateAttendanceInteractors->execute($attendanceRequest, $attendance);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully updated.');
    }

    public function destroy(DeleteAttendanceInteractors $deleteAttendanceInteractors, string $id): RedirectResponse
    {
        $deleteAttendanceInteractors->execute($id);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully deleted.');
    }

    public function upload(AttendanceCsvRequest $attendanceCsvRequest): RedirectResponse
    {
        $file = $attendanceCsvRequest->file('csv_file');

        $filename = uniqid().'_'.time().'.csv';

        $path = Storage::disk('local')->putFileAs('temp', $file, $filename);
        if ($path === false) {
            throw new \RuntimeException('The attendance CSV could not be stored.');
        }

        $attendanceImport = AttendanceImport::query()->create([
            'user_id' => $attendanceCsvRequest->user()->id,
            'file_path' => $path,
        ]);

        ProcessAttendanceCsv::dispatch($attendanceImport->id, $path, $attendanceCsvRequest->user()->id);

        return redirect()->route('attendance.index')
            ->with('attendance_import_id', $attendanceImport->id)
            ->with('success', 'CSV is being processed in the background.');
    }
}
