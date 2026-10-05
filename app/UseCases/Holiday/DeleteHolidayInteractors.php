<?php

namespace App\UseCases\Holiday;

use App\Models\Holiday;

class DeleteHolidayInteractors
{
    public function execute(string $id): bool
    {
        $holiday = Holiday::findOrFail($id);

        return $holiday->delete();
    }
}
