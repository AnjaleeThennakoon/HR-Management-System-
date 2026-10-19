<?php
namespace App\UseCases\LeaveApproval;

use App\Models\LeaveApproval;
use App\UseCases\LeaveApproval\Request\LeaveApprovalRequest;

Class UpdateLeaveApprovalInterctors
{
    public function execute(LeaveApprovalRequest $leaveApprovalRequest,LeaveApproval $leaveApproval):LeaveApproval {
        $leaveApproval->update($leaveApprovalRequest->validated());

        return $leaveApproval->refresh();
    }
}
