<?php

namespace App\UseCases\Report;

use App\Models\Attendance;
use Carbon\Carbon;
use Date;

class ShowReportInteractor
{
    public function execute(Date $date): array
    {
        $attendances = Attendance::with('employee')->whereDate('date', $date)->get();

        return $attendances->map(function ($attendance) {

            $inTime = $attendance->in_time;
            $outTime = $attendance->out_time;

            return [
                'employee_name' => $attendance->employee->name,
                'in_time' => $inTime,
                'out_time' => $outTime,
                'work_hours' => $outTime - $inTime,
                'attendance_date' => $attendance->date,
                'sending_date' => $attendance->created_at,
            ];
        })->toArray();
    }
}
