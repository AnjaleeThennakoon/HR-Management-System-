<?php

namespace app\UseCases\Employee;

use App\Models\Employee;

class DeleteEmployeeInteractors
{
    public function execute(string $id): bool
    {
        $Employee = Employee::findOrFail($id);

        return $Employee->delete();
    }
}
