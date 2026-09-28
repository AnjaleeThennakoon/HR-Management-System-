<?php

namespace App\UseCases\Designation;

use App\Models\Designation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateDesignationInteractor
{
    public function execute(string $id, array $designationData): Designation
    {
        return DB::transaction(function () use ($id, $designationData) {
            $designation = Designation::findOrFail($id);
            $newUpperLevel = $designationData['upper_level'] ?? null;

            if ($newUpperLevel && $this->wouldCreateLoop($designation, $newUpperLevel)) {
                throw ValidationException::withMessages([
                    'upper_level' => 'This selection creates a hierarchy loop.',
                ]);
            }

            if ($newUpperLevel) {
                $parent = Designation::findOrFail($newUpperLevel);
                $designationData['level'] = $parent->level + 1;
            } else {
                $designationData['upper_level'] = null;
                $designationData['level'] = 1;
            }

            $designation->update($designationData);

            $designation->refresh()->recalculateDescendantsLevels();

            return $designation->refresh();
        });
    }

    private function wouldCreateLoop(Designation $designation, int $newUpperLevelId): bool
    {
        if ($designation->id === $newUpperLevelId) return true;

        $current = $newUpperLevelId;
        $visited = [];
        while ($current) {
            if ($current == $designation->id) return true;
            if (in_array($current, $visited)) return true;
            $visited[] = $current;
            $parent = Designation::find($current);
            $current = $parent?->upper_level;
        }
        return false;
    }
}
