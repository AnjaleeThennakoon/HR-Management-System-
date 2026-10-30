<?php

namespace App\UseCases\Payroll;

use App\Models\Employee;
use App\UseCases\Attendance\MonthlyAttendanceFinalized;
use App\UseCases\Payroll\Request\PayrollRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;

class GeneratePayrollInteractors
{
    public function execute(PayrollRequest $payrollRequest): view
    {
        $validated = $payrollRequest->validated();
        $employee = Employee::findOrFail($validated['employee_id']);

        $finalized = MonthlyAttendanceFinalized::where('employee_id', $employee->id)
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->first();


        if (!$finalized) {
            throw ValidationException::withMessages([
                'month' => 'This month is not finalized yet.',
            ]);
        }
    }
}
