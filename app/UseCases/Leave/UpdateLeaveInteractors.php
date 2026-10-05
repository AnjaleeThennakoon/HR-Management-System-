<?php

namespace App\UseCases\Leave;

use App\Models\Leave;

class UpdateLeaveInteractors
{
    public function execute(string $id, array $leaveData): Leave
    {
        $leave = Leave::findOrFail($id);

        $leave->update($leaveData);

        return $leave->refresh();
    }
}
