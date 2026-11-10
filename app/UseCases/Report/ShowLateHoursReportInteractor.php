<?php

namespace App\UseCases\Report;

use Carbon\Carbon;
use App\Models\Attendance;

class ShowLateHoursReportInteractor
{
    /**
     * @param array{start: string, end: string} $dateRange
     * @return array{dateRange: array{start: string, end: string}, lateHours: array<int, array>}
     */
    public function execute(array $dateRange): array
    {
        $start = Carbon::parse($dateRange['start'])->startOfDay();
        $end   = Carbon::parse($dateRange['end'])->endOfDay();

        $lateEmployees = [];

        $attendances = Attendance::with('employee')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get();

        foreach ($attendances as $attendance) {
            if (!$attendance->employee || !$attendance->in_time) {
                continue;
            }

            $scheduledIn = Carbon::parse($attendance->date . ' ' . $attendance->employee->shift_in);
            $actualIn    = Carbon::parse($attendance->date . ' ' . $attendance->in_time);

            if ($actualIn->greaterThan($scheduledIn)) {
                $lateMinutes = (int) $scheduledIn->diffInMinutes($actualIn);

                $lateEmployees[] = [
                    'employee'     => trim($attendance->employee->first_name . ' ' . $attendance->employee->last_name),
                    'date'         => $attendance->date,
                    'scheduled_in' => $scheduledIn->format('H:i'),
                    'actual_in'    => $actualIn->format('H:i'),
                    'late_minutes' => $lateMinutes,
                    'employee_id'  => $attendance->employee->id,
                ];

                $attendance->update(['late_minutes' => $lateMinutes]);
            }
        }

        return [
            'dateRange' => ['start' => $start->toDateString(), 'end' => $end->toDateString()],
            'lateHours' => $lateEmployees,
        ];
    }
}
