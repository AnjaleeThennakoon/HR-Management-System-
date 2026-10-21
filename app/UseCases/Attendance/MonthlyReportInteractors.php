<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class MonthlyReportInteractors
{
    public function execute(array $validated): array
    {
        $selectedMonth = $validated['month'] ?? date('Y-m');
        $selectedEmployeeId = $validated['employee_id'] ?? null;

        $employees = Employee::all();
        $employee = null;
        $attendances = [];

        if ($selectedEmployeeId) {
            $employee = Employee::findOrFail($selectedEmployeeId);

            $startDate = Carbon::parse($selectedMonth)->startOfMonth();
            $endDate = Carbon::parse($selectedMonth)->endOfMonth();

            $rawAttendances = Attendance::where('employee_id', $selectedEmployeeId)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->get();

            foreach ($rawAttendances as $attendance) {
                $inTime = Carbon::parse($attendance->in_time);
                $outTime = $attendance->out_time ? Carbon::parse($attendance->out_time) : null;

                $shiftIn = Carbon::parse($employee->shift_in ?? '08:00:00');
                $shiftOut = Carbon::parse($employee->shift_out ?? '17:00:00');

                $lateMinutes = 0;
                if ($inTime->greaterThan($shiftIn)) {
                    $lateMinutes = (int) $inTime->diffInMinutes($shiftIn, true);
                }

                $otMinutes = 0;
                if ($outTime && $outTime->greaterThan($shiftOut)) {
                    $otMinutes = (int) $outTime->diffInMinutes($shiftOut, true);
                }

                $attendances[] = (object) [
                    'date' => $attendance->date,
                    'in_time' => $attendance->in_time,
                    'out_time' => $attendance->out_time,
                    'late_minutes' => $lateMinutes,
                    'ot_minutes' => $otMinutes,
                ];
            }
        }

        return [
            'employees' => $employees,
            'employee' => $employee,
            'attendances' => $attendances,
            'selectedMonth' => $selectedMonth,
            'selectedEmployeeId' => $selectedEmployeeId,
        ];

    }
}
