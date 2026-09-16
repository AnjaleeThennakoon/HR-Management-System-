<?php

namespace App\UseCases\Department;

use App\Models\Department;

class StoreDepartmentInteractors
{
    public function execute(array $data): Department
    {
        return Department::create($data);
    }
}
