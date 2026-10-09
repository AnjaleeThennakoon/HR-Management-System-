<?php

namespace App\UseCases\Leave;

use App\Models\Leave;
use App\UseCases\Leave\Request\LeaveRequest;

class StoreLeaveInteractors
{
    public function execute(LeaveRequest $leaveRequest): Leave
    {
        $data = $leaveRequest->validated();

        return Leave::create([
            'employee_id' => $data['employee_id'],
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? $data['start_date'],
            'reason' => $data['reason'],
            'status' => $data['status'] ?? 'pending',
        ]);
    }
}
