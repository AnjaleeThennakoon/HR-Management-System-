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
        if (!empty($designation['upper_level'])) {
            $upperLevelDesignation = Designation::findOrFail(
                $designation['upper_level']
            );

            $newLevel = $upperLevelDesignation->level + 1;

            $hasSibling = Designation::where(
                'upper_level',
                $upperLevelDesignation->id
            )->exists();

            if (!$hasSibling) {
                Designation::where('level', '>=', $newLevel)
                    ->increment('level');
            }
            $designation['level'] = $newLevel;

        } else {
            $designation['level'] = (Designation::max('level') ?? 0) + 1;
        }

        return $designation;
    }
}
