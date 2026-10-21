<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\UseCases\Attendance\Request\MonthlyReportRequest;
use Carbon\Carbon;

class MonthlyReportInteractors
{
    public function execute(MonthlyReportRequest $monthlyReportRequest): array
    {
        $validated = $monthlyReportRequest->validated();
        $month = $validated['month'] ?? date('Y-m');
        $selectedYear = $validated['year'] ?? date('Y');

        if (str_contains((string) $month, '-')) {
            [$selectedYear, $month] = explode('-', $month);
        }

        $selectedYear = (int) $selectedYear;
        $selectedMonthNum = (int) $month;
        $selectedMonth = sprintf('%04d-%02d', $selectedYear, $selectedMonthNum);

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonthNum, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $employees = Employee::orderBy('first_name')->get();

        $holidays = Holiday::whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])->get();

        $employee = null;
        $attendances = [];
        $leaves = collect();
        $totalLateMinutes = 0;
        $totalOtMinutes = 0;

        $selectedEmployeeId = $validated['employee_id'] ?? null;

        if ($selectedEmployeeId) {
            $employee = Employee::find($selectedEmployeeId);

            if ($employee) {
                $leaves = $this->getEmployeeLeaves(
                    $selectedEmployeeId,
                    $startDate,
                    $endDate
                );

                $attendances = $this->getEmployeeAttendances(
                    $employee,
                    $startDate,
                    $endDate
                );

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

    private function getEmployeeLeaves(
        int|string $employeeId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return Leave::where('employee_id', $employeeId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])
                    ->orWhereBetween('end_date', [
                        $startDate->toDateString(),
                        $endDate->toDateString(),
                    ])
                    ->orWhere(function ($subQuery) use ($startDate, $endDate) {
                        $subQuery->where('start_date', '<=', $startDate->toDateString())
                            ->where('end_date', '>=', $endDate->toDateString());
                    });
            })
            ->get();
    }

    private function getEmployeeAttendances(
        Employee $employee,
        Carbon $startDate,
        Carbon $endDate
    ): array {
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderBy('date')
            ->get();

        $shiftIn = Carbon::parse($employee->shift_in ?? '08:00:00');
        $shiftOut = Carbon::parse($employee->shift_out ?? '17:00:00');

        $report = [];

        foreach ($attendances as $attendance) {
            $inTime = $attendance->in_time
                ? Carbon::parse($attendance->in_time)
                : null;

            $outTime = $attendance->out_time
                ? Carbon::parse($attendance->out_time)
                : null;

            $lateMinutes = $inTime && $inTime->greaterThan($shiftIn)
                ? (int) $inTime->diffInMinutes($shiftIn, true)
                : 0;

            $otMinutes = $outTime && $outTime->greaterThan($shiftOut)
                ? (int) $outTime->diffInMinutes($shiftOut, true)
                : 0;

            $dateKey = Carbon::parse($attendance->date)->toDateString();

            $report[$dateKey] = (object) [
                'date' => $attendance->date,
                'in_time' => $attendance->in_time,
                'out_time' => $attendance->out_time,
                'late_minutes' => $lateMinutes,
                'ot_minutes' => $otMinutes,
            ];
        }

        return $report;
    }
}
