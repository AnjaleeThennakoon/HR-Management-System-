<?php

namespace App\UseCases\Employee;

use App\Models\Employee;

class StoreEmployeeInteractors
{
    public function execute(array $employee): Employee
    {
        return Employee::create($employee);
    }
}
