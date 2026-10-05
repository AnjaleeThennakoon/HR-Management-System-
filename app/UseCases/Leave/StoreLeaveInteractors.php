<?php

namespace App\UseCases\Leave;

use App\Models\Leave;
use App\UseCases\Leave\Request\LeaveRequest;

class StoreLeaveInteractors
{
    public function execute(LeaveRequest $leaveRequest): Leave
    {
        return Leave::create($leaveRequest->validated());
    }
}
