<?php

namespace App\UseCases\Payroll;

use App\Models\Employee;
use App\Models\MonthlyAttendanceFinalized;
use App\UseCases\Payroll\Request\PayrollRequest;
use Illuminate\Validation\ValidationException;

class GeneratePayrollInteractor
{
    /**
     * @return array{employee_id: int, month: int, year: int, basic_salary: float, allowances: float, deductions: float, net_salary: float, EPF: float}
     */
    public function execute(PayrollRequest $payrollRequest): array
    {
        $validated = $payrollRequest->validated();

        if (
            $validated['year'] > now()->year ||
            (
                $validated['year'] == now()->year &&
                $validated['month'] > now()->month
            )
        ) {
            throw ValidationException::withMessages([
                'month' => 'Cannot process next month salary.',
            ]);
        }

        $employee = Employee::findOrFail($validated['employee_id']);
        $basicSalary = (float) $employee->salary;
        $dailyRate = $basicSalary / 26;

        $finalizedMonth = MonthlyAttendanceFinalized::where('employee_id', $employee->id)
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->first();

        if (! $finalizedMonth) {
            throw ValidationException::withMessages([
                'month' => 'This month is not finalized yet.',
            ]);
        }

        $totalLateMinutes = (int) $finalizedMonth->total_late_minutes;
        $totalOtMinutes = (int) $finalizedMonth->total_ot_minutes;
        $totalNoPayDays = (int) $finalizedMonth->total_no_pay_days;
        $hourlyRate = $dailyRate / 8;

        $epf = $basicSalary * 0.08;

        $allowances = ($totalOtMinutes / 60) * $hourlyRate;
        $lateDeduction = ($totalLateMinutes / 60) * $hourlyRate;
        $noPayDeduction = $totalNoPayDays * $dailyRate;
        $deductions = $lateDeduction + $noPayDeduction + $epf;
        $netSalary = ($basicSalary + $allowances) - $deductions;

        return [
            'employee_id' => $employee->id,
            'month' => (int) $validated['month'],
            'year' => (int) $validated['year'],
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'net_salary' => $netSalary,
            'EPF' => $epf,
        ];
    }
}
