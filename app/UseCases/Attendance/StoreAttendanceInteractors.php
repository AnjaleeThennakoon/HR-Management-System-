<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\UseCases\Attendance\Request\AttendanceRequest;

class StoreAttendanceInteractors
{
    public function execute(AttendanceRequest $attendanceRequest): Attendance
    {
        return Attendance::create(
            $attendanceRequest->validated()
        );
    }
}
