<?php

namespace App\UseCases\Holiday;

use App\Models\Leave;

class DeleteHolidayInteractors
{
    public function execute(string $id): bool
    {
        $holiday = Leave::findOrFail($id);

        return $holiday->delete();
    }
}
