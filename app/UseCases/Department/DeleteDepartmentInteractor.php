<?php

namespace App\UseCases\Department;

use App\Models\Department;

class DeleteDepartmentInteractor
{
    public function execute(string $id): bool
    {
        $department = Department::findOrFail($id);

        return $department->delete();
    }
}
