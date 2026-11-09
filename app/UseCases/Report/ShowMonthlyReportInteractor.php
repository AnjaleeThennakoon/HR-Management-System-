<?php

namespace App\UseCases\Report;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;

class ShowMonthlyReportInteractor
{
    public function execute(string $month): array
    {
        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd   = $monthStart->copy()->endOfMonth();

        $attendances = Attendance::query()
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get()
            ->groupBy('employee_id');

        $leavesByEmployee = Leave::query()
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $monthEnd->toDateString())
            ->whereDate('end_date', '>=', $monthStart->toDateString())
            ->get(['employee_id', 'start_date', 'end_date'])
            ->groupBy('employee_id');

        $workingDays = collect(range(1, $monthStart->daysInMonth))
            ->filter(fn (int $day) => ! $monthStart->copy()->day($day)->isWeekend())
            ->count();

        $employees = Employee::query()
            ->orderBy('employee_id')
            ->get()
            ->map(function (Employee $employee) use ($attendances, $leavesByEmployee, $monthStart, $monthEnd, $workingDays): array {
                $records = $attendances->get($employee->id, collect());

                $presentCount = $records->filter(
                    fn (Attendance $a) => $a->in_time && $a->out_time
                )->count();

                $overtimeMinutes = (int) $records->sum('ot_minutes');
                $lateMinutes     = (int) $records->sum('late_minutes');

                $leaveDays = $leavesByEmployee->get($employee->id, collect())
                    ->sum(function (Leave $leave) use ($monthStart, $monthEnd): int {
                        $leaveStart = Carbon::parse($leave->start_date);
                        $leaveEnd   = Carbon::parse($leave->end_date);
                        $overlapStart = $leaveStart->greaterThan($monthStart) ? $leaveStart : $monthStart;
                        $overlapEnd   = $leaveEnd->lessThan($monthEnd)       ? $leaveEnd   : $monthEnd;
                        return $overlapStart->diffInDays($overlapEnd) + 1;
                    });

                $absentCount = max(0, $workingDays - $presentCount);

                return [
                    'employee_id' => $employee->employee_id,
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'ot_hours' => round($overtimeMinutes / 60, 2),
                    'ot_minutes' => $overtimeMinutes,
                    'late_minutes'  => $lateMinutes,
                    'leave_days' => $leaveDays,
                    'status' => $presentCount > 0 ? 'Present' : 'Absent',
                ];
            });

        return [
            'employees' => $employees->all(),
            'totals' => [
                'present_days' => $employees->sum('present_count'),
                'absent_days'  => $employees->sum('absent_count'),
                'late_minutes' => $attendances->flatten()->sum('late_minutes'),
                'ot_minutes'   => $attendances->flatten()->sum('ot_minutes'),
                'leave_days'   => $employees->sum('leave_days'),
                'work_hours'   => $attendances->flatten()->sum(function (Attendance $a): int {
                    if (! $a->in_time || ! $a->out_time) return 0;
                    return (int) Carbon::parse($a->in_time)->diffInHours(Carbon::parse($a->out_time));
                }),
            ],
        ];
    }
}
