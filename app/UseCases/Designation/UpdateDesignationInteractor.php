<?php

namespace App\UseCases\Designation;

use App\Models\Designation;

class UpdateDesignationInteractor
{
    public function execute(
        string $id,
        array $data
    ): Designation {
        $designation = Designation::findOrFail($id);

        $designation->update($data);

        return $designation->refresh();
    }
}
