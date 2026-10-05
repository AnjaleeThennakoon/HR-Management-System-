<?php

namespace App\UseCases\Leave;

use App\Models\Leave;

class UpdateLeaveInteractors
{
    public function execute(string $id, array $LeaveData): Leave
    {
        $holiday = Leave::findOrFail($id);

        $holiday->update($leaveData);

        return $holiday->refresh();
    }
}
