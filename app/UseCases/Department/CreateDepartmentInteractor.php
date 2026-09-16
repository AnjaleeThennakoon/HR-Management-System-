<?php

namespace App\UseCases\Department;

use App\Models\Department;

class CreateDepartmentInteractor
{
    public function execute(array $data): Department
    {
        return Department::create([
            'name' => $data['name'],
        ]);
    }
}
