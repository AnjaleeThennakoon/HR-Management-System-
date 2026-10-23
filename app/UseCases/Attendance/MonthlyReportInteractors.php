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

        $month      = (int) $validated['month'];
        $year       = (int) $validated['year'];
        $employeeID = (int) $validated['employee_id'];

        $attendance = Attendance::with('employee')
            ->where('employee_id', $employeeID)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn ($a) => $a->date instanceof Carbon
                ? $a->date->format('Y-m-d')
                : $a->date);

        foreach ($attendance as $record) {
            $this->calculateLateAndOT($record);
        }

        return [
            'employee_id'   => $employeeID,
            'month'         => $month,
            'year'          => $year,
            'attendances'   => $attendance,
            'employees'     => Employee::all(),
            'employee'      => Employee::find($employeeID),
            'holidays'      => Holiday::whereMonth('date', $month)->whereYear('date', $year)->get(),
            'leaves'        => Leave::where('employee_id', $employeeID)->get(),
            'selectedMonth' => $month,
            'selectedYear'  => $year,
        ];
    }

    private function LateAndOTTotal($attendance): array
    {
        $totalLate = 0;
        $totalOt   = 0;

        foreach ($attendance as $record) {
            $totalLate += $record->late_minutes;
            $totalOt   += $record->ot_minutes;
        }

        return [$totalLate, $totalOt];
    }

    private function calculateLateAndOT(Attendance $attendance): void
    {
        $attendance->late_minutes = 0;
        $attendance->ot_minutes   = 0;

        if (!$attendance->in_time || !$attendance->employee?->shift_in) {
            return;
        }

        $inTime  = Carbon::parse($attendance->in_time);
        $shiftIn = Carbon::parse($attendance->employee->shift_in);

        if ($inTime->greaterThan($shiftIn)) {
            $attendance->late_minutes = (int) abs($inTime->diffInMinutes($shiftIn));
        }

        if (!$attendance->out_time || !$attendance->employee?->shift_out) {
            return;
        }

        $outTime  = Carbon::parse($attendance->out_time);
        $shiftOut = Carbon::parse($attendance->employee->shift_out);

        if ($outTime->greaterThan($shiftOut)) {
            $attendance->ot_minutes = (int) abs($outTime->diffInMinutes($shiftOut));
        }
    }
}
