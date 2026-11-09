<?php

namespace App\UseCases\Report;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\MonthlyAttendanceFinalized;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LeveMonthlyReportInteractor
{
    public function execute(string $month): Collection
    {
        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd   = $monthStart->copy()->endOfMonth();

        $monthNumber = (int) $monthStart->month;
        $year        = (int) $monthStart->year;

        $noPayDaysByEmployee = MonthlyAttendanceFinalized::query()
            ->where('month', $monthNumber)
            ->where('year', $year)
            ->get(['employee_id', 'total_no_pay_days'])
            ->groupBy('employee_id')
            ->map(fn ($records) => $records->sum('total_no_pay_days'));

        $leavesByEmployee = Leave::query()
            ->whereDate('start_date', '<=', $monthEnd->toDateString())
            ->whereDate('end_date', '>=', $monthStart->toDateString())
            ->whereIn('status', ['approved', 'pending', 'rejected', 'non-pay'])
            ->get(['employee_id', 'start_date', 'end_date', 'reason', 'status'])
            ->groupBy('employee_id');

        return Employee::orderBy('employee_id')
            ->get()
            ->map(function (Employee $employee) use ($leavesByEmployee, $noPayDaysByEmployee, $monthStart, $monthEnd): array {
                $employeeLeaves = $leavesByEmployee->get($employee->id, collect());

                return [
                    'employee_id' => $employee->employee_id,
                    'first_name'  => $employee->first_name,
                    'last_name'   => $employee->last_name,
                    'no_pay_days' => $noPayDaysByEmployee->get($employee->id, 0),
                    'leaves'      => $this->mergeConsecutiveLeaves($employeeLeaves, $monthStart, $monthEnd),
                ];
            });
    }
    private function mergeConsecutiveLeaves(Collection $leaves, Carbon $monthStart, Carbon $monthEnd): array
    {
        $normalized = $leaves->map(function (Leave $leave) use ($monthStart, $monthEnd) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd   = Carbon::parse($leave->end_date);
            $overlapStart = $leaveStart->greaterThan($monthStart) ? $leaveStart : $monthStart;
            $overlapEnd   = $leaveEnd->lessThan($monthEnd)       ? $leaveEnd   : $monthEnd;

            return [
                'start_date' => $overlapStart->toDateString(),
                'end_date'   => $overlapEnd->toDateString(),
                'reason'     => $leave->reason,
                'status'     => $leave->status,
            ];
        })->sortBy('start_date')->values();

        $merged = [];
        foreach ($normalized as $leave) {
            $i = count($merged) - 1;
            if ($i >= 0
                && $merged[$i]['reason'] === $leave['reason']
                && $merged[$i]['status'] === $leave['status']
                && Carbon::parse($merged[$i]['end_date'])->addDay()->toDateString() === $leave['start_date']
            ) {
                $merged[$i]['end_date'] = $leave['end_date'];
            } else {
                $merged[] = $leave;
            }
        }

        return $merged;
    }

}
