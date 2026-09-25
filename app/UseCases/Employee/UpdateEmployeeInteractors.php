<?php

namespace App\UseCases\Employee;

use App\Models\Employee;
use App\UseCases\Employee\Request\EmployeeRequest;

class UpdateEmployeeInteractors
{
    public function execute(EmployeeRequest $employeeRequest, Employee $employee)
    {
        $employee->update($employeeRequest->validated());

        return $employee->fresh();
    }
}
