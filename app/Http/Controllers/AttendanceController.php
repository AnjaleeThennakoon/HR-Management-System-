<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\UseCases\Attendance\ListAttendanceInteractors;
use App\UseCases\Attendance\Request\AttendanceRequest;
use Illuminate\Contracts\View\Factory;
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
        $storeAttendanceInteractors->execute($attendanceRequest ->validated());

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance has been successfully created.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
