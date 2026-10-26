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

        $month = (int) $validated['month'];
        $year  = (int) $validated['year'];
        $employeeID = $validated['employee_id'];

        $attendance = Attendance::with('employee')
            ->where('employee_id', $employeeID)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn ($record) => $record->date instanceof Carbon
                ? $record->date->format('Y-m-d')
                : $record->date);

        foreach ($attendance as $record) {
            $this->calculateLateAndOT($record);
            $record->save();
        }

        return [
            'employee_id'  => $employeeID,
            'month'     => $month,
            'year'    => $year,
            'attendances'  => $attendance,
            'employees'   => Employee::all(),
            'employee'   => Employee::find($employeeID),
            'holidays'    => Holiday::whereMonth('date', $month)->whereYear('date', $year)->get(),
            'leaves'    => Leave::where('employee_id', $employeeID)->get(),
            'selectedMonth'  => $month,
            'selectedYear'   => $year,
            'totalLateMinutes' => $attendance->sum('late_minutes'),
            'totalOtMinutes'   => $attendance->sum('ot_minutes'),
        ];
    }

    private function calculateLateAndOT(Attendance $attendance): void
    {
        $attendance->late_minutes = 0;
        $attendance->ot_minutes   = 0;

        $employee = $attendance->employee;
        if (!$employee) {
            return;
        }

        if ($attendance->in_time && $employee->shift_in) {
            $inTime  = Carbon::parse($attendance->in_time);
            $shiftIn = Carbon::parse($employee->shift_in);

            if ($inTime->greaterThan($shiftIn)) {
                $attendance->late_minutes = (int) $shiftIn->diffInMinutes($inTime);
            }
        }

        if ($attendance->out_time && $employee->shift_out) {
            $outTime  = Carbon::parse($attendance->out_time);
            $shiftOut = Carbon::parse($employee->shift_out);

            if ($shiftOut->lessThanOrEqualTo($shiftIn ?? Carbon::parse($employee->shift_in))) {
                $shiftOut->addDay();
            }

            if ($outTime->greaterThan($shiftOut)) {
                $attendance->ot_minutes = (int) $shiftOut->diffInMinutes($outTime);
            }
        }
    }

}
