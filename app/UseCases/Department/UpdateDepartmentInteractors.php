<?php

namespace App\UseCases\Department;

use App\Models\Department;

class UpdateDepartmentInteractors
{
    public function execute(string $id, array $data): Department
    {
        $department = Department::findOrFail($id);

        $department->update($data);

        return $department->refresh();

    }
}
