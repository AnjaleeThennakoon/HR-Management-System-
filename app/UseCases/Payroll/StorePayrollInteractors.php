<?php

namespace App\UseCases\Payroll;

use App\Models\Payroll;
use App\UseCases\Payroll\Request\PayrollRequest;

class StorePayrollInteractors
{
    public function __construct(private GeneratePayrollInteractors $generatePayrollInteractors)
    {
    }

    public function execute(PayrollRequest $payrollRequest): Payroll
    {
        $payrollData = $this->generatePayrollInteractors->execute($payrollRequest);

        return Payroll::create($payrollData);
    }
}
