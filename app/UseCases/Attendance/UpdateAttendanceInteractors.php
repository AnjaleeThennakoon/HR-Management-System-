<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\UseCases\Attendance\Request\AttendanceRequest;


class UpdateAttendanceInteractors
{
    public function execute(AttendanceRequest $attendanceRequest,Attendance $attendance):Attendance
    {
        $attendance = Attendance::findOrFail($attendance->id);

        $attendance->update($attendanceRequest->validated());

        return $attendance->refresh();
    }
}
