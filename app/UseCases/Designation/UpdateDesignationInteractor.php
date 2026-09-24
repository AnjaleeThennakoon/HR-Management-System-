<?php

namespace App\UseCases\Designation;
use App\Models\Designation;
class UpdateDesignationInteractor
{
    public function execute(string $id, array $designationData): Designation {
        $designation = Designation::findOrFail($id);
        $designation->update($designationData);
        return $designation->refresh();
    }
}
