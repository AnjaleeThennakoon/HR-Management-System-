<?php

namespace App\UseCases\Holiday;

use App\Models\Holiday;

class DeleteHolidayInteractor
{
    public function execute(string $id): bool
    {
        $holiday = Holiday::findOrFail($id);

        return $holiday->delete();
    }
}
