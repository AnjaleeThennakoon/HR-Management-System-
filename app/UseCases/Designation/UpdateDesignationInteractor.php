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
            $this->updateHierarchy($designation, $designationData['upper_level'] ?? null);
            $designation->update($designationData);
            return $designation->refresh();
        });
    }
    private function updateHierarchy(Designation $designation, ?int $newUpperLevel): void {
        if ($designation->upper_level === $newUpperLevel) {return;}
        $designationUnderNewUpper = Designation::where('upper_level', $newUpperLevel)->whereKeyNot($designation->id)->first();
        $designationUnderCurrent = Designation::where('upper_level', $designation->id)->first();
        $designationUnderNewUpper?->update(['upper_level' => $designation->id,]);
        $designationUnderCurrent?->update(['upper_level' => $designationUnderNewUpper?->id,]);
    }

}

