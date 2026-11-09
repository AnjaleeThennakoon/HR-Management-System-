<?php

namespace App\UseCases\Designation;

use App\Models\Designation;

class DeleteDesignationInteractor
{
    public function execute(string $id): void
    {
        $designation = Designation::findOrFail($id);

        $designation->delete();
    }
}
