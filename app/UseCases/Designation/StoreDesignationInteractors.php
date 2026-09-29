<?php

namespace App\UseCases\Designation;

use App\Models\Designation;
use App\UseCases\Designation\Request\DesignationRequest;

class StoreDesignationInteractors
{
    public function execute(DesignationRequest $designationRequest): Designation
    {
        $designation = $this->getDesignationWithNewLevel(
            $designationRequest->validated()
        );
        return Designation::create($designation);
    }

    private function getDesignationWithNewLevel(array $designation): array
    {
        if (empty($designation['upper_level'])) {
            $designation['level'] = $this->getNextLevel();

            return $designation;
        }

        $parent = Designation::findOrFail($designation['upper_level']);
        $designation['level'] = $parent->level + 1;
        $this->shiftLevelsForFirstChild($parent, $designation['level']);
        return $designation;
    }

    private function getNextLevel(): int
    {
        return (Designation::max('level') ?? 0) + 1;
    }

    private function shiftLevelsForFirstChild(Designation $parent, int $level): void {
        $hasSibling = Designation::where('upper_level', $parent->id)->exists();

        if ($hasSibling) {
            return;
        }

        Designation::where('level', '>=', $level)->increment('level');
    }
}
