<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\MonthlyAttendanceFinalized as FinalizedAttendanceRecord;
use App\UseCases\Attendance\Request\MonthlyReportRequest;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class MonthlyAttendanceFinalized
{
    /**
     * @throws \JsonException
     */
    public function execute(MonthlyReportRequest $request): FinalizedAttendanceRecord
    {
        $validated = $request->validated();
        $employee = Employee::findOrFail($validated['employee_id']);
        $month = (int) $validated['month'];
        $year = (int) $validated['year'];

        $this->validateAllDaysAccountedFor($employee, $month, $year);
        $attendanceHash = $this->getMonthAttendanceHash($employee->id, $month, $year);
        $finalizedMonth = FinalizedAttendanceRecord::query()
            ->where('employee_id', $employee->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($finalizedMonth && hash_equals((string) $finalizedMonth->attendance_hash, $attendanceHash)) {
            throw ValidationException::withMessages([
                'month' => 'This month has already been finalized.',
            ]);
        }

        $finalizationData = [
            'total_late_minutes' => $validated['total_late_minutes'] ?? 0,
            'total_ot_minutes' => $validated['total_ot_minutes'] ?? 0,
            'total_no_pay_days' => $validated['total_no_pay_days'] ?? 0,
            'finalized_by' => auth()->id(),
            'finalized_at' => now(),
            'attendance_hash' => $attendanceHash,
        ];

        if ($finalizedMonth) {
            $finalizedMonth->update($finalizationData);

            return $finalizedMonth->refresh();
        }

        return FinalizedAttendanceRecord::create([
            'employee_id' => $employee->id,
            'month' => $month,
            'year' => $year,
            ...$finalizationData,
        ]);
    }

    /**
     * @throws \JsonException
     */
    public function isFinalized(int $employeeId, int $month, int $year): bool
    {
        $finalizedMonth = FinalizedAttendanceRecord::query()
            ->where('employee_id', $employeeId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if (! $finalizedMonth) {
            return false;
        }

        try {
            $employee = Employee::findOrFail($employeeId);
            $this->validateAllDaysAccountedFor($employee, $month, $year);
        } catch (ValidationException $e) {
            return false;
        }

        $attendanceHash = $this->getMonthAttendanceHash($employeeId, $month, $year);

        if (empty($finalizedMonth->attendance_hash)) {
            $finalizedMonth->update(['attendance_hash' => $attendanceHash]);

            return true;
        }

        return hash_equals($finalizedMonth->attendance_hash, $attendanceHash);
    }

    private function getMonthAttendanceHash(int $employeeId, int $month, int $year): string
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $attendance = Attendance::query()
            ->where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('id')
            ->get(['id', 'date', 'in_time', 'out_time'])
            ->map(fn (Attendance $record): array => [
                'id' => $record->id,
                'date' => Carbon::parse($record->date)->toDateString(),
                'in_time' => $record->in_time,
                'out_time' => $record->out_time,
            ])
            ->all();

        $leaves = Leave::query()
            ->where('employee_id', $employeeId)
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->orderBy('id')
            ->get(['id', 'start_date', 'end_date', 'leave_type', 'status'])
            ->map(fn (Leave $leave): array => [
                'id' => $leave->id,
                'start_date' => Carbon::parse($leave->start_date)->toDateString(),
                'end_date' => Carbon::parse($leave->end_date ?? $leave->start_date)->toDateString(),
                'leave_type' => $leave->leave_type,
                'status' => $leave->status,
            ])
            ->all();

        $holidays = Holiday::query()
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('id')
            ->get(['id', 'date', 'name', 'type'])
            ->map(fn (Holiday $holiday): array => [
                'id' => $holiday->id,
                'date' => Carbon::parse($holiday->date)->toDateString(),
                'name' => $holiday->name,
                'type' => $holiday->type,
            ])
            ->all();

        return hash('sha256', json_encode([
            'attendance' => $attendance,
            'leaves' => $leaves,
            'holidays' => $holidays,
        ], JSON_THROW_ON_ERROR));
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
