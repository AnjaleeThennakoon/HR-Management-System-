<?php

namespace App\UseCases\LeaveApproval;

use App\Models\LeaveApproval;
use App\UseCases\LeaveApproval\Request\LeaveApprovalRequest;

class StoreLeaveApprovalInteractors
{
    public function execute(LeaveApprovalRequest $leaveApprovalRequest): LeaveApproval
    {
        $leaveApprovalData = $leaveApprovalRequest->validated();

        return LeaveApproval::create([
            'leave_id' => $leaveApprovalData['leave_id'],
            'employee_id' => $leaveApprovalData['employee_id'],
            'status' => $leaveApprovalData['status'],
            'remarks' => $leaveApprovalData['remarks'] ?? null,
            'approved_at' => $leaveApprovalData['approved_at'],
        ]);
    }
}
