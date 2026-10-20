<?php

namespace app\UseCases\Employee;

use App\Models\Employee;

class DeleteEmployeeInteractors
{
    public function execute(string $id): bool
    {
        $employee = Employee::findOrFail($id);

        return $employee->delete();
    }
}
