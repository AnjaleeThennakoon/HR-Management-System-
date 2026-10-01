<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;

class StoreAttendanceInteractors
{
    public function execute(array $attendancedata): Attendance
    {
        return Attendance::create($attendancedata);
    }
}
