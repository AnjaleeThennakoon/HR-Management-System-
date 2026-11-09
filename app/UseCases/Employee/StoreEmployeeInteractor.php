<?php

namespace App\UseCases\Employee;

use App\Models\Employee;

class StoreEmployeeInteractor
{
    public function execute(array $employee): Employee
    {
        return Employee::create($employee);
    }
}
