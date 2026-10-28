<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\FinalizedMonth;
use App\Models\Holiday;
use App\Models\Leave;
use App\UseCases\Attendance\Request\MonthlyReportRequest;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class FinalizedMonthInteractors
{
    public function execute(MonthlyReportRequest $request): FinalizedMonth
    {
        $validated = $request->validated();
        $employee = Employee::findOrFail($validated['employee_id']);
        $month = (int) $validated['month'];
        $year = (int) $validated['year'];

        $this->ensureNotAlreadyFinalized($employee->id, $month, $year);
        $this->validateAllDaysAccountedFor($employee, $month, $year);

        return FinalizedMonth::create([
            'employee_id' => $employee->id,
            'month' => $month,
            'year' => $year,
            'total_late_minutes' => $validated['total_late_minutes'] ?? 0,
            'total_ot_minutes' => $validated['total_ot_minutes'] ?? 0,
            'total_no_pay_days' => $validated['total_no_pay_days'] ?? 0,
            'finalized_by' => auth()->id(),
            'finalized_at' => now(),
        ]);
    }

    private function ensureNotAlreadyFinalized(int $employeeId, int $month, int $year): void
    {
        $isFinalized = FinalizedMonth::where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->exists();

        if ($isFinalized) {
            throw ValidationException::withMessages([
                'month' => 'This month has already been finalized.',
            ]);
        }
    }

    private function validateAllDaysAccountedFor(Employee $employee, int $month, int $year): void
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $totalDays = $startOfMonth->daysInMonth;
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn ($record) => Carbon::parse($record->date)->format('Y-m-d'));
        $holidays = Holiday::whereMonth('date', $month)->whereYear('date', $year)->get();
        $leaves = Leave::where('employee_id', $employee->id)->get();

        for ($day = 1; $day <= $totalDays; $day++) {
            $dateObj = Carbon::createFromDate($year, $month, $day);
            $dateStr = $dateObj->toDateString();

            if ($dateObj->isWeekend()) {
                continue;
            }
            if ($holidays->contains(fn ($h) => Carbon::parse($h->date)->toDateString() === $dateStr)) {
                continue;
            }
            $isLeave = $leaves->contains(function ($l) use ($dateStr) {
                $start = Carbon::parse($l->start_date)->toDateString();
                $end = Carbon::parse($l->end_date ?? $l->start_date)->toDateString();

                return $dateStr >= $start && $dateStr <= $end;
            });
            if ($isLeave) {
                continue;
            }

            $record = $attendance->get($dateStr);
            if (! $record || (empty($record->in_time) && empty($record->out_time))) {
                throw ValidationException::withMessages([
                    'month' => "Cannot finalize. The working day {$dateStr} has no attendance record.",
                ]);
            }
        }
    }
}
