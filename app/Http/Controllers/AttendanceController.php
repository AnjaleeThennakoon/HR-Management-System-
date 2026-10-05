<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\UseCases\Attendance\DeleteAttendanceInteractors;
use App\UseCases\Attendance\ListAttendanceInteractors;
use App\UseCases\Attendance\Request\AttendanceCsvRequest;
use App\UseCases\Attendance\Request\AttendanceRequest;
use App\UseCases\Attendance\StoreAttendanceInteractors;
use App\UseCases\Attendance\UpdateAttendanceInteractors;
use App\UseCases\Attendance\UploadAttendanceInteractors;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(ListAttendanceInteractors $listAttendanceInteractors): View
    {
        $attendances = $listAttendanceInteractors->execute(
            request('search'),
            request('per_page')
        );

        return view('Attendance.AttendanceDashbord', [
            'attendances' => $attendances,
            'employees' => Employee::all(),
        ]);
    }

    public function store(AttendanceRequest $attendanceRequest, StoreAttendanceInteractors $storeAttendanceInteractors): RedirectResponse
    {
        try {
            $storeAttendanceInteractors->execute($attendanceRequest->validated());

            return redirect()->route('attendance.index')
                ->with('success', 'Attendance has been successfully created.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
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

    public function upload(AttendanceCsvRequest $attendanceCsvRequest, UploadAttendanceInteractors $uploadAttendanceInteractors): RedirectResponse
    {
        $uploadAttendanceInteractors->execute(
            $attendanceCsvRequest->file('csv_file')
        );

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance CSV imported successfully.');
    }
}
