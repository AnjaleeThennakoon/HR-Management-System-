<?php

namespace App\UseCases\Employee;

use App\Models\Employee;
use App\UseCases\Employee\Request\EmployeeRequest;

class UpdateEmployeeInteractors
{
    public function execute(EmployeeRequest $employeeRequest, Employee $employee): Employee
    {
        $data = collect($employeeRequest->validated())
            ->except(['employee_id', 'nic'])
            ->all();

        $employee->update($data);

        return $employee->fresh();
    }
}
