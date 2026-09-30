<?php

namespace App\UseCases\Holiday;

use App\Models\Holiday;

class UpdateHolidayInteractors
{
    public function execute(string $id, array $holidayData): Holiday
    {
        $holiday = Holiday::findOrFail($id);

        $holiday->update($holidayData);

        return $holiday->refresh();
    }
}
