<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\UseCases\Attendance\DeleteAttendanceInteractors;
use App\UseCases\Attendance\ListAttendanceInteractors;
use App\UseCases\Attendance\MonthlyReportInteractors;
use App\UseCases\Attendance\Request\AttendanceCsvRequest;
use App\UseCases\Attendance\Request\AttendanceRequest;
use App\UseCases\Attendance\Request\MonthlyReportRequest;
use App\UseCases\Attendance\StoreAttendanceInteractors;
use App\UseCases\Attendance\UpdateAttendanceInteractors;
use App\UseCases\Attendance\UploadAttendanceInteractors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(ListAttendanceInteractors $listAttendanceInteractors): View
    {
        $importId = request()->integer('import') ?: (int) session('attendance_import_id', 0);
        $attendances = $listAttendanceInteractors->execute(request('search'), request('per_page'));
        $attendanceImport = $listAttendanceInteractors->getImportStatus($importId, auth()->user()?->employee_id);

        return view('Attendance.AttendanceDashbord', [
            'attendances' => $attendances,
            'employees' => Employee::all(),
            'attendanceImport' => $attendanceImport,
        ]);
    }

    public function importStatus(Request $request, int $id, ListAttendanceInteractors $listAttendanceInteractors): JsonResponse
    {
        $attendanceImport = $listAttendanceInteractors->getImportStatus($id, $request->user()?->employee_id);

        return response()->json([
            'status' => $attendanceImport->status,
            'summary' => $attendanceImport->summary,
            'rows' => $attendanceImport->rows,
            'error_message' => $attendanceImport->error_message,
        ]);
    }

    public function store(AttendanceRequest $attendanceRequest, StoreAttendanceInteractors $storeAttendanceInteractors): RedirectResponse|JsonResponse
    {
        $attendance = $storeAttendanceInteractors->execute($attendanceRequest->validated());

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok', 'attendance' => $attendance]);
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully created.');
    }

    public function update(string $id, AttendanceRequest $attendanceRequest, UpdateAttendanceInteractors $updateAttendanceInteractors): RedirectResponse|JsonResponse
    {
        $attendance = Attendance::findOrFail($id);
        $attendance = $updateAttendanceInteractors->execute($attendanceRequest, $attendance);

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok', 'attendance' => $attendance]);
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully updated.');
    }

    public function destroy(DeleteAttendanceInteractors $deleteAttendanceInteractors, string $id): RedirectResponse
    {
        $deleteAttendanceInteractors->execute($id);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully deleted.');
    }

    public function upload(AttendanceCsvRequest $attendanceCsvRequest, UploadAttendanceInteractors $uploadAttendanceInteractors): RedirectResponse
    {
        $attendanceImport = $uploadAttendanceInteractors->upload(
            $attendanceCsvRequest->file('csv_file'),
            auth()->user()?->employee_id
        );

        return redirect()->route('attendance.index')
            ->with('attendance_import_id', $attendanceImport->id)
            ->with('success', 'CSV is being processed in the background.');
    }

    public function monthlyReport(MonthlyReportRequest $monthlyReportRequest, MonthlyReportInteractors $monthlyReportInteractors): View
    {
        $data = $monthlyReportInteractors->execute($monthlyReportRequest);

        return view('Attendance.MonthlyReport', $data);
    }
}
