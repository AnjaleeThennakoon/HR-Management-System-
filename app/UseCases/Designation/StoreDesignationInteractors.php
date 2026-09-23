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
            $upperLevelDesignation = Designation::findOrfail(
                $designation['upper_level']
            );
            $designation['level'] = $upperLevelDesignation->level + 1;
        }

        if (Designation::count() == 0) {
            $designation['level'] = 1;
        } else {
            $designation['level'] = Designation::max('level') + 1;
        }

        return $designation;
    }
}
