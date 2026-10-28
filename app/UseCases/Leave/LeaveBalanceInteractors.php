<?php

namespace App\UseCases\Leave;

use App\Models\Support\LeaveSupport;

class LeaveBalanceInteractors
{
    public function execute(array $validated): array
    {
        $employeeId = (int) $validated['employee_id'];
        $excludeLeaveId = isset($validated['exclude_leave_id']) ? (int) $validated['exclude_leave_id'] : null;

        if (! empty($validated['leave_type'])) {
            return [
                'success' => true,
                'balance' => LeaveSupport::getBalance($employeeId, $validated['leave_type'], null, $excludeLeaveId),
            ];
        }

        return [
            'success' => true,
            'balances' => [
                'Annual' => LeaveSupport::getBalance($employeeId, 'Annual', null, $excludeLeaveId),
                'Medical' => LeaveSupport::getBalance($employeeId, 'Medical', null, $excludeLeaveId),
                'casual' => LeaveSupport::getBalance($employeeId, 'casual', null, $excludeLeaveId),
            ],
        ];
    }
}
