<?php
namespace App\UseCases\LeaveApproval;

use App\Models\Leave;

class DeleteLeaveApprovalInteractors
{
    public function execute(string $id): bool
    {
        $leave = Leave::findOrFail($id);

        return $leave->delete();
    }
}
