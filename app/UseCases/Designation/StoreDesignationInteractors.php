<?php

namespace App\UseCases\Designation;

use App\Models\Designation;
use App\UseCases\Designation\Request\DesignationRequest;

class StoreDesignationInteractors
{
    public function execute(DesignationRequest $designationRequest): Designation
    {
        $designation = $designationRequest->validated();

        $designation = $this->getDesignationWithNewLevel($designation);

        return Designation::create($designation);
    }

    public function getDesignationWithNewLevel(array $designation): array
    {
        if (isset($designation['upper_level'])) {
            $designation['level'] = Designation::findOrFail($designation['upper_level'])->level + 1;
        }else{
            $designation['level'] =  (Designation::max('level') ?? 0) + 1;
        }

        return $designation;
    }
}
