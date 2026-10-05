<?php

namespace App\UseCases\Leave;

use App\Models\Leave;
use App\UseCases\Leave\Request\LeaveRequest;

class UpdateLeaveInteractors
{
    public function execute(LeaveRequest $leaveRequest, Leave $leave ): Leave
    {

        $leave->update($leaveRequest-> validated());

        return $leave->refresh();
    }
}
