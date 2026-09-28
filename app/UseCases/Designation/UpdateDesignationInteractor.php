<?php

namespace App\UseCases\Designation;

use App\Models\Designation;
use Illuminate\Support\Facades\DB;

class UpdateDesignationInteractor
{
    public function execute(string $id, array $designationData): Designation
    {
        return DB::transaction(function () use ($id, $designationData) {

            $designation = Designation::findOrFail($id);

            if (!empty($designationData['upper_level'])) {
                $parent = Designation::findOrFail($designationData['upper_level']);
                $designationData['level'] = $parent->level + 1;
            } else {
                $designationData['level'] = (Designation::max('level') ?? 0) + 1;
            }

            $this->updateHierarchy($designation, $designationData['upper_level'] ?? null);

            $designation->update($designationData);

            return $designation->refresh();
        });
    }

    private function updateHierarchy(Designation $designation, ?int $newUpperLevel): void
    {
        if ($designation->upper_level === $newUpperLevel) {return;}
        $designationUnderNewUpper = Designation::where('upper_level', $newUpperLevel)->whereKeyNot($designation->id)
            ->first();
        $designationUnderCurrent = Designation::where('upper_level', $designation->id)->first();
        $designationUnderNewUpper?->update(['upper_level' => $designation->id]);
        $designationUnderCurrent?->update(['upper_level' => $designationUnderNewUpper?->id]);
    }
}
