<?php

namespace App\UseCases\Designation;

use App\Models\Designation;

class StoreDesignationInteractors
{
    public function execute(array $designation): Designation
    {
        return Designation::create($designation);
    }
}
