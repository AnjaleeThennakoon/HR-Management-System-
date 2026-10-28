<?php

namespace App\UseCases\Leave;

use App\Models\Leave;
use App\UseCases\Leave\Request\LeaveRequest;

class UpdateLeaveInteractors
{
    public function execute(LeaveRequest $leaveRequest, Leave $leave): Leave
    {
        $data = $leaveRequest->validated();

        if (array_key_exists('status', $data)) {
            if (in_array($data['status'], ['approved', 'rejected'])) {
                $data['approved_at'] = $leave->approved_at ?? now();
                $data['user_id'] = $leave->user_id ?? (int) auth()->id();
            } elseif ($data['status'] === 'pending') {
                $data['approved_at'] = null;
                $data['user_id'] = null;
            }
        }

        $leave->update($data);

        return $leave->refresh();
    }
}
