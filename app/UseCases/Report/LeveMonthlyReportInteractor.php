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
            ->map(function (Employee $employee) use ($leavesByEmployee, $noPayDaysByEmployee): array {
                $employeeLeaves = $leavesByEmployee->get($employee->id, collect());

                return [
                    'employee_id' => $employee->employee_id,
                    'first_name'  => $employee->first_name,
                    'last_name'   => $employee->last_name,
                    'no_pay_days' => $noPayDaysByEmployee->get($employee->id, 0),
                    'leaves'      => $employeeLeaves->map(fn (Leave $leave) => [
                        'start_date' => $leave->start_date,
                        'end_date'   => $leave->end_date,
                        'reason'     => $leave->reason,
                        'status'     => $leave->status,
                    ])->values()->all(),
                ];
            });
    }
}
