<?php

namespace App\UseCases\Holiday;

use App\Models\Leave;

class UpdateLeaveInteractors
{
    public function execute(string $id, array $holidayData): Leave
    {
        $holiday = Leave::findOrFail($id);

        $holiday->update($holidayData);

        return $holiday->refresh();
    }
}
