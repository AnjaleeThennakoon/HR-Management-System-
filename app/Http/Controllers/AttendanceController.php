<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\UseCases\Attendance\DeleteAttendanceInteractor;
use App\UseCases\Attendance\ListAttendanceInteractor;
use App\UseCases\Attendance\MonthlyAttendanceFinalizedInteractor;
use App\UseCases\Attendance\MonthlyReportInteractor;
use App\UseCases\Attendance\Request\AttendanceCsvRequest;
use App\UseCases\Attendance\Request\AttendanceRequest;
use App\UseCases\Attendance\Request\MonthlyReportRequest;
use App\UseCases\Attendance\StoreAttendanceInteractor;
use App\UseCases\Attendance\UpdateAttendanceInteractor;
use App\UseCases\Attendance\UploadAttendanceInteractor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(ListAttendanceInteractor $listAttendanceInteractor): View
    {
        $importId = request()->integer('import') ?: (int) session('attendance_import_id', 0);
        $attendances = $listAttendanceInteractor->execute(request('search'), request('per_page'));
        $attendanceImport = $listAttendanceInteractor->getImportStatus($importId, auth()->user()?->employee_id);

        return view('Attendance.AttendanceDashbord', [
            'attendances' => $attendances,
            'employees' => Employee::all(),
            'attendanceImport' => $attendanceImport,
        ]);
    }

    public function importStatus(Request $request, int $id, ListAttendanceInteractor $listAttendanceInteractor): JsonResponse
    {
        $attendanceImport = $listAttendanceInteractor->getImportStatus($id, $request->user()?->employee_id);

        return response()->json([
            'status' => $attendanceImport->status,
            'summary' => $attendanceImport->summary,
            'rows' => $attendanceImport->rows,
            'error_message' => $attendanceImport->error_message,
        ]);
    }

    public function store(AttendanceRequest $attendanceRequest, StoreAttendanceInteractor $storeAttendanceInteractor): RedirectResponse|JsonResponse
    {
        $attendance = $storeAttendanceInteractor->execute($attendanceRequest->validated());

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok', 'attendance' => $attendance]);
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully created.');
    }

    public function update(string $id, AttendanceRequest $attendanceRequest, UpdateAttendanceInteractor $updateAttendanceInteractor): RedirectResponse|JsonResponse
    {
        $attendance = Attendance::findOrFail($id);
        $attendance = $updateAttendanceInteractor->execute($attendanceRequest, $attendance);

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok', 'attendance' => $attendance]);
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully updated.');
    }

    public function destroy(DeleteAttendanceInteractor $deleteAttendanceInteractor, string $id): RedirectResponse|JsonResponse
    {
        $deleteAttendanceInteractor->execute($id);

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully deleted.');
    }

    public function upload(AttendanceCsvRequest $attendanceCsvRequest, UploadAttendanceInteractor $uploadAttendanceInteractor): RedirectResponse
    {
        $attendanceImport = $uploadAttendanceInteractor->upload($attendanceCsvRequest->file('csv_file'), auth()->user()?->employee_id);

        return redirect()->route('attendance.index')
            ->with('attendance_import_id', $attendanceImport->id)
            ->with('success', 'CSV is being processed in the background.');
    }

    public function monthlyReport(MonthlyReportRequest $monthlyReportRequest, MonthlyReportInteractor $monthlyReportInteractor): View
    {
        $data = $monthlyReportInteractor->execute($monthlyReportRequest);

        return view('Attendance.MonthlyReport', $data);
    }

    public function finalizedMonth(MonthlyReportRequest $monthlyReportRequest, MonthlyAttendanceFinalizedInteractor $finalizedMonthInteractor): RedirectResponse
    {
        $finalizedMonthInteractor->execute($monthlyReportRequest);

        return redirect()->back()->with('success', 'Month finalized successfully!');

    }
}
