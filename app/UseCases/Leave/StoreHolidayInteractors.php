<?php

namespace App\UseCases\Leave;

use App\Models\Leave;

class StoreLeaveInteractors
{
    public function execute(LeaveRequest $leaveRequest)
    {
        $leave = $leaveRequest->validated();

        return Leave::create($leave);
    }
}
