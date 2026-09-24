<?php

namespace App\UseCases\Department;

use App\Models\Department;

class UpdateDepartmentInteractors
{
    public function execute(string $id, array $departmentData): Department
    {
        $department = Department::findOrFail($id);

        $department->update($departmentData);

        return $department->refresh();

    }
}
