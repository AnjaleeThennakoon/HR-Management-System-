<?php
namespace App\UseCases\Employee;
use App\Models\Employee;

class UpdateEmployeeInteractors{
    public function execute(string $id, array $employeeData): Employee
    {
        $employee = Employee::findOrFail($id);
        $employee->update($employeeData);
        return $employee->refresh();
    }

}
