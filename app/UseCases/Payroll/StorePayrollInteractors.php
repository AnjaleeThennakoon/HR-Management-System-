<?php

namespace App\UseCases\Payroll;

use App\Models\Employee;
use App\Models\MonthlyAttendanceFinalize;
use App\Models\Payroll;
use App\UseCases\Payroll\Request\PayrollRequest;
use Illuminate\Validation\ValidationException;

class StorePayrollInteractors
{
    public function execute(PayrollRequest $payrollRequest): Payroll
    {
        $validated = $payrollRequest->validated();
        $employee = Employee::findOrFail($validated['employee_id']);
        $finalizedMonth = MonthlyAttendanceFinalize::where('employee_id', $employee->id)
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->first();

        if (! $finalizedMonth) {
            throw ValidationException::withMessages([
                'month' => 'Cannot generate payroll. The attendance for this month is not finalized yet.',
            ]);
        }

        return Payroll::create([
            'employee_id' => $employee->id,
            'month' => $validated['month'],
            'year' => $validated['year'],
            'basic_salary' => $employee->salary,
            'allowances' => 0,
            'deductions' => 0,
            'net_salary' => $employee->salary,
        ]);
    }
}
