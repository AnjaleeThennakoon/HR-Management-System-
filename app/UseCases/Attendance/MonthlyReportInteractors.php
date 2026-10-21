<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use Carbon\Carbon;

class MonthlyReportInteractors
{
    public function execute(array $validated): array
    {
        $selectedYear = ! empty($validated['year']) ? (int) $validated['year'] : (int) date('Y');
        $rawMonth = $validated['month'] ?? null;

        if (is_string($rawMonth) && str_contains($rawMonth, '-')) {
            $parts = explode('-', $rawMonth);
            $selectedYear = (int) $parts[0];
            $selectedMonthNum = (int) $parts[1];
        } elseif (! empty($rawMonth)) {
            $selectedMonthNum = (int) $rawMonth;
        } else {
            $selectedMonthNum = (int) date('n');
        }

        $selectedMonth = sprintf('%04d-%02d', $selectedYear, $selectedMonthNum);
        $selectedEmployeeId = $validated['employee_id'] ?? null;

        $employees = Employee::orderBy('first_name')->get();
        $employee = null;
        $attendances = [];
        $leaves = collect();
        $totalLateMinutes = 0;
        $totalOtMinutes = 0;

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonthNum, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $holidays = Holiday::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->get();

        if ($selectedEmployeeId) {
            $employee = Employee::find($selectedEmployeeId);

            if ($employee) {
                $leaves = Leave::where('employee_id', $selectedEmployeeId)
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
                            ->orWhereBetween('end_date', [$startDate->toDateString(), $endDate->toDateString()])
                            ->orWhere(function ($sub) use ($startDate, $endDate) {
                                $sub->where('start_date', '<=', $startDate->toDateString())
                                    ->where('end_date', '>=', $endDate->toDateString());
                            });
                    })
                    ->get();

                $rawAttendances = Attendance::where('employee_id', $selectedEmployeeId)
                    ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                    ->orderBy('date', 'asc')
                    ->get();

                $shiftIn = Carbon::parse($employee->shift_in ?? '08:00:00');
                $shiftOut = Carbon::parse($employee->shift_out ?? '17:00:00');

                foreach ($rawAttendances as $attendance) {
                    $inTime = $attendance->in_time ? Carbon::parse($attendance->in_time) : null;
                    $outTime = $attendance->out_time ? Carbon::parse($attendance->out_time) : null;

                    $lateMinutes = 0;
                    if ($inTime && $inTime->greaterThan($shiftIn)) {
                        $lateMinutes = (int) $inTime->diffInMinutes($shiftIn, true);
                    }

                    $otMinutes = 0;
                    if ($outTime && $outTime->greaterThan($shiftOut)) {
                        $otMinutes = (int) $outTime->diffInMinutes($shiftOut, true);
                    }

                    $dateKey = Carbon::parse($attendance->date)->toDateString();

                    $attendances[$dateKey] = (object) [
                        'date' => $attendance->date,
                        'in_time' => $attendance->in_time,
                        'out_time' => $attendance->out_time,
                        'late_minutes' => $lateMinutes,
                        'ot_minutes' => $otMinutes,
                    ];
                }
                $totalLateMinutes = collect($attendances)->sum('late_minutes');
                $totalOtMinutes = collect($attendances)->sum('ot_minutes');
            }
        }

        return [
            'employees' => $employees,
            'employee' => $employee,
            'attendances' => $attendances,
            'holidays' => $holidays,
            'leaves' => $leaves,
            'selectedYear' => $selectedYear,
            'selectedMonthNum' => $selectedMonthNum,
            'selectedMonth' => $selectedMonth,
            'selectedEmployeeId' => $selectedEmployeeId,
            'totalLateMinutes' => $totalLateMinutes,
            'totalOtMinutes' => $totalOtMinutes,
        ];
    }
}
