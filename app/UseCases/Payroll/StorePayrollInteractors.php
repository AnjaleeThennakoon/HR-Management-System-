<?php

namespace App\UseCases\Payroll;

use App\Models\Payroll;
use App\UseCases\Payroll\Request\PayrollRequest;

class StorePayrollInteractors
{

    public function execute(PayrollRequest $payrollRequest, GeneratePayrollInteractors $generatePayrollInteractors): Payroll
    {
        $payrollData = $generatePayrollInteractors->execute($payrollRequest);

        return Payroll::create($payrollData);
    }
}
