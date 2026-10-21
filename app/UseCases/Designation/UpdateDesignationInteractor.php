<?php

namespace App\UseCases\Designation;

use App\Models\Designation;
use App\UseCases\Designation\Request\DesignationRequest;
use Illuminate\Support\Facades\DB;

class UpdateDesignationInteractor
{
    public function execute(DesignationRequest $designationRequest): Designation
    {
        $designationData = $designationRequest->validated();

        return DB::transaction(function () use ($designationRequest, $designationData) {
            $designation = Designation::findOrFail($designationRequest->route('id'));
            $oldLevel = $designation->level;

            if (array_key_exists('upper_level', $designationData)) {
                if (! empty($designationData['upper_level'])) {
                    $parent = Designation::findOrFail($designationData['upper_level']);
                    $designationData['level'] = $parent->level + 1;
                } else {
                    $designationData['level'] = (Designation::max('level') ?? 0) + 1;
                }

                if ($designationData['level'] !== $oldLevel) {
                    Designation::where('level', '>', $oldLevel)
                        ->whereKeyNot($designation->id)
                        ->decrement('level');

                    Designation::where('level', '>=', $designationData['level'])
                        ->whereKeyNot($designation->id)
                        ->increment('level');
                }

                $this->updateHierarchy($designation, $designationData['upper_level']);
            }

            $designation->update($designationData);

            return $designation->refresh();
        });
    }

    private function updateHierarchy(Designation $designation, ?int $newUpperLevel): void
    {
        if ($designation->upper_level === $newUpperLevel) {
            return;
        }
        $designationUnderNewUpper = Designation::where('upper_level', $newUpperLevel)->whereKeyNot($designation->id)
            ->first();
        $designationUnderCurrent = Designation::where('upper_level', $designation->id)->first();
        $designationUnderNewUpper?->update(['upper_level' => $designation->id]);
        $designationUnderCurrent?->update(['upper_level' => $designationUnderNewUpper?->id]);
    }
}
