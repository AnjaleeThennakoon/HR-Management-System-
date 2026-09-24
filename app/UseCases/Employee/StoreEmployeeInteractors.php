<?php

namespace App\UseCases\Employee;

use App\Models\Employee;

class StoreEmployeeInteractors
{
    public function execute(array $employeedata): Employee
    {
        return Employee::create($employeedata);
    }
}
