<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\UseCases\Attendance\DeleteAttendanceInteractors;
use App\UseCases\Attendance\ListAttendanceInteractors;
use App\UseCases\Attendance\MonthlyAttendanceFinalizedInteractors;
use App\UseCases\Attendance\MonthlyReportInteractors;
use App\UseCases\Attendance\Request\AttendanceRequest;
use App\UseCases\Attendance\Request\MonthlyReportRequest;
use App\UseCases\Attendance\StoreAttendanceInteractors;
use App\UseCases\Attendance\UpdateAttendanceInteractors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(ListAttendanceInteractors $listAttendanceInteractors): View
    {
        $attendances = $listAttendanceInteractors->execute(request('search'), request('per_page'));

        return view('Attendance.AttendanceDashbord', [
            'attendances' => $attendances,
            'employees' => Employee::all(),
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

    public function destroy(DeleteAttendanceInteractors $deleteAttendanceInteractors, string $id): RedirectResponse|JsonResponse
    {
        $deleteAttendanceInteractors->execute($id);

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully deleted.');
    }

    public function checkIn(): RedirectResponse
    {
        $validated = request()->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ]);

        $alreadyCheckedIn = Attendance::query()
            ->where('employee_id', $validated['employee_id'])
            ->whereDate('date', now()->toDateString())
            ->exists();

        if ($alreadyCheckedIn) {
            return redirect()->route('attendance.index')->withErrors([
                'employee_id' => 'Attendance is already recorded for this employee today.',
            ]);
        }

        Attendance::query()->create([
            'employee_id' => $validated['employee_id'],
            'date' => now()->toDateString(),
            'in_time' => now()->format('H:i:s'),
        ]);

        return redirect()->route('attendance.index')
            ->with('success', 'Employee checked in successfully.');
    }

    public function checkOut(): RedirectResponse
    {
        $validated = request()->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ]);

        $attendance = Attendance::query()
            ->where('employee_id', $validated['employee_id'])
            ->whereDate('date', now()->toDateString())
            ->whereNotNull('in_time')
            ->whereNull('out_time')
            ->first();

        if (! $attendance) {
            return redirect()->route('attendance.index')->withErrors([
                'employee_id' => 'No open check-in was found for this employee today.',
            ]);
        }

        $attendance->update(['out_time' => now()->format('H:i:s')]);

        return redirect()->route('attendance.index')
            ->with('success', 'Employee checked out successfully.');
    }

    public function monthlyReport(MonthlyReportRequest $monthlyReportRequest, MonthlyReportInteractors $monthlyReportInteractors): View
    {
        $data = $monthlyReportInteractors->execute($monthlyReportRequest);

        return view('Attendance.MonthlyReport', $data);
    }

    public function finalizedMonth(MonthlyReportRequest $monthlyReportRequest, MonthlyAttendanceFinalizedInteractors $finalizedMonthInteractors): RedirectResponse
    {
        $finalizedMonthInteractors->execute($monthlyReportRequest);

        return redirect()->back()->with('success', 'Month finalized successfully!');

    }
}
