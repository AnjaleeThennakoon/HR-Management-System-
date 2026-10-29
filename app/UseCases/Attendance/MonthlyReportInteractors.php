<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\FinalizedMonth;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Support\LeaveSupport;
use App\Models\SystemConfiguration;
use App\UseCases\Attendance\Request\MonthlyReportRequest;
use Carbon\Carbon;

class MonthlyReportInteractors
{
    public function execute(MonthlyReportRequest $monthlyReportRequest): array
    {
        $validated = $monthlyReportRequest->validated();
        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);
        $employeeID = $validated['employee_id'] ?? null;

        $employee = $employeeID ? Employee::find($employeeID) : null;
        $holidays = Holiday::whereMonth('date', $month)->whereYear('date', $year)->get();
        $finalizedMonth = $employeeID ? FinalizedMonth::where('employee_id', $employeeID)
            ->where('month', $month)
            ->where('year', $year)
            ->first() : null;
        $monthName = Carbon::createFromDate($year, $month, 1)->format('F');

        if ($employee) {
            $attendance = Attendance::with('employee')
                ->where('employee_id', $employeeID)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get()
                ->keyBy(fn ($record) => $record->date instanceof Carbon
                    ? $record->date->format('Y-m-d')
                    : Carbon::parse($record->date)->format('Y-m-d'));

            foreach ($attendance as $record) {
                $this->calculateLateAndOT($record);
                $record->save();
            }

            $leaves = Leave::where('employee_id', $employeeID)->get();

            $nonWorkingDaysCount = $this->checkNonWorkingDay($month, $year, $attendance, $holidays, $leaves);

            $leaveBalances = [
                'Annual' => LeaveSupport::getRemainingDays($employeeID, 'Annual', $year),
                'Medical' => LeaveSupport::getRemainingDays($employeeID, 'Medical', $year),
                'casual' => LeaveSupport::getRemainingDays($employeeID, 'casual', $year),
            ];
            $totalRemainingLeaveDays = array_sum($leaveBalances);
            $allLeaveBalancesUsed = ($totalRemainingLeaveDays <= 0);
            $nonPayDaysCount = $allLeaveBalancesUsed
                ? $nonWorkingDaysCount
                : max(0, $nonWorkingDaysCount - $totalRemainingLeaveDays);

        } else {
            $attendance = collect();
            $leaves = collect();
            $nonWorkingDaysCount = 0;
            $leaveBalances = ['Annual' => 0, 'Medical' => 0, 'casual' => 0];
            $totalRemainingLeaveDays = 0;
            $allLeaveBalancesUsed = false;
            $nonPayDaysCount = 0;
        }

        return [
            'employee_id' => $employeeID,
            'month' => $month,
            'year' => $year,
            'monthName' => $monthName,
            'isFinalized' => (bool) $finalizedMonth,
            'finalizedMonth' => $finalizedMonth,
            'hasEmptyDays' => $nonWorkingDaysCount > 0,
            'attendances' => $attendance,
            'employees' => Employee::all(),
            'employee' => $employee,
            'holidays' => $holidays,
            'leaves' => $leaves,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'totalLateMinutes' => $attendance->sum('late_minutes'),
            'totalOtMinutes' => $attendance->sum('ot_minutes'),
            'nonWorkingDays' => $nonWorkingDaysCount,
            'nonPayDays' => $nonPayDaysCount,
            'totalRemainingLeaveDays' => $totalRemainingLeaveDays,
            'allLeaveBalancesUsed' => $allLeaveBalancesUsed,
            'leaveBalances' => $leaveBalances,
            'leaveCounts' => [
                'Annual' => SystemConfiguration::getLeaveCount('Annual'),
                'Medical' => SystemConfiguration::getLeaveCount('Medical'),
                'casual' => SystemConfiguration::getLeaveCount('casual'),
            ],
        ];
    }

    private function calculateLateAndOT(Attendance $attendance): void
    {
        $attendance->late_minutes = 0;
        $attendance->ot_minutes = 0;

        $employee = $attendance->employee;
        if (! $employee) {
            return;
        }

        if ($attendance->in_time && $employee->shift_in) {
            $inTime = Carbon::parse($attendance->in_time);
            $shiftIn = Carbon::parse($employee->shift_in);

            if ($inTime->greaterThan($shiftIn)) {
                $attendance->late_minutes = (int) $shiftIn->diffInMinutes($inTime);
            }
        }

        if ($attendance->out_time && $employee->shift_out) {
            $outTime = Carbon::parse($attendance->out_time);
            $shiftOut = Carbon::parse($employee->shift_out);

            if ($outTime->greaterThan($shiftOut)) {
                $attendance->ot_minutes = (int) $shiftOut->diffInMinutes($outTime);
            }
        }
    }

    private function checkNonWorkingDay(int $month, int $year, $attendance, $holidays, $leaves): int
    {
        $nonWorkingDays = collect();
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $totalDays = $startOfMonth->daysInMonth;

        for ($day = 1; $day <= $totalDays; $day++) {
            $dateObj = Carbon::createFromDate($year, $month, $day);
            $dateStr = $dateObj->toDateString();

            if ($dateObj->isWeekend()) {
                continue;
            }

            $isHoliday = $holidays->contains(fn ($holiday) => Carbon::parse($holiday->date)->toDateString() === $dateStr);
            if ($isHoliday) {
                continue;
            }

            $isLeave = $leaves->contains(function ($leave) use ($dateStr) {
                $leaveStart = Carbon::parse($leave->start_date)->toDateString();
                $leaveEnd = Carbon::parse($leave->end_date ?? $leave->start_date)->toDateString();

                return $dateStr >= $leaveStart && $dateStr <= $leaveEnd;
            });
            if ($isLeave) {
                continue;
            }

            $record = $attendance->get($dateStr);

            if (! $record || (empty($record->in_time) && empty($record->out_time))) {
                $nonWorkingDays->push($dateStr);
            }
        }

        return $nonWorkingDays->count();
    }
}
