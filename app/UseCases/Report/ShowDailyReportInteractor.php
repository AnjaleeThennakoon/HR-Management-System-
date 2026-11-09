<?php

namespace App\UseCases\Report;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class ShowDailyReportInteractor
{
    public function execute(string $date): array
    {
        $attendances = Attendance::whereDate('date', $date)
            ->get()
            ->keyBy('employee_id');

        return Employee::orderBy('employee_id')
            ->get()
            ->map(function (Employee $employee) use ($attendances, $date) {
                $attendance = $attendances->get($employee->id);

                $inTime = $attendance?->in_time;
                $outTime = $attendance?->out_time;

                $workingHours = ($inTime && $outTime)
                    ? (int) Carbon::parse($inTime)->diffInHours(Carbon::parse($outTime))
                    : 0;

                $status = match (true) {
                    $inTime && $outTime => 'Present',
                    $inTime || $outTime => 'Incomplete',
                    default => 'Absent',
                };

                return [
                    'employee_id' => $employee->employee_id,
                    'attendance_id' => $attendance?->id,
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'in_time' => $inTime,
                    'out_time' => $outTime,
                    'work_hours' => $workingHours,
                    'ot_minutes' => $attendance?->ot_minutes ?? 0,
                    'late_minutes' => $attendance?->late_minutes ?? 0,
                    'status' => $status,
                    'attendance_date' => $attendance?->date ?? $date,
                ];
            })
            ->toArray();
    }
}
