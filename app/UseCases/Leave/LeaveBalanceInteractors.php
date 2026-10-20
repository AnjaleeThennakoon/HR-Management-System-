<?php

namespace App\UseCases\Leave;

use App\Models\Support\LeaveSupport;

class LeaveBalanceInteractors
{
    public function execute(array $validated): array
    {
        $employeeId = (int) $validated['employee_id'];

        if (! empty($validated['leave_type'])) {
            return [
                'success' => true,
                'balance' => LeaveSupport::getBalance($employeeId, $validated['leave_type']),
            ];
        }

        return [
            'success' => true,
            'balances' => [
                'Annual' => LeaveSupport::getBalance($employeeId, 'Annual'),
                'Medical' => LeaveSupport::getBalance($employeeId, 'Medical'),
                'casual' => LeaveSupport::getBalance($employeeId, 'casual'),
            ],
        ];
    }
}
