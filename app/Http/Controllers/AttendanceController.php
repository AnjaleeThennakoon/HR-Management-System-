<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\UseCases\Attendance\DeleteAttendanceInteractors;
use App\UseCases\Attendance\ListAttendanceInteractors;
use App\UseCases\Attendance\Request\AttendanceRequest;
use App\UseCases\Attendance\StoreAttendanceInteractors;
use App\UseCases\Attendance\UpdateAttendanceInteractors;
use App\UseCases\Department\DeleteDepartmentInteractors;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceRequest $attendanceRequest, StoreAttendanceInteractors $storeAttendanceInteractors): RedirectResponse
    {
        $storeAttendanceInteractors->execute($attendanceRequest->validated());

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully created.');

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceInteractors $updateAttendanceInteractors,AttendanceRequest $attendanceRequest, String $id): RedirectResponse
    {
        $attendance = Attendance::findOrFail($id);
        $updateAttendanceInteractors->execute($attendanceRequest, $attendance);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteAttendanceInteractors $deleteAttendanceInteractors, String $id): RedirectResponse
    {
        $deleteAttendanceInteractors->execute($id);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully deleted.');

    }
}
