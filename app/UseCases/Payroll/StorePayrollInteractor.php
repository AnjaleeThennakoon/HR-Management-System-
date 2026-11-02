<?php

namespace App\UseCases\Payroll;

use App\Models\Payroll;
use App\UseCases\Payroll\Request\PayrollRequest;

class StorePayrollInteractor
{
    public function __construct(private GeneratePayrollInteractor $generatePayrollInteractor) {}

    public function execute(PayrollRequest $payrollRequest): Payroll
    {
        $payrollData = $this->generatePayrollInteractor->execute($payrollRequest);

        return Payroll::create($payrollData);
    }
}
