<?php

namespace App\UseCases\Leave\Request;

use App\Models\Leave;
use App\Models\SystemConfiguration;
use Carbon\Carbon;
use Validator;

class LeaveValidator extends Validator
{
    public static function getRemainingDays(
        int $employeeId,
        string $leaveType,
        ?int $year = null,
        ?int $excludeLeaveId = null
    ): int {
        $year = $year ?? now()->year;

        $yearStart = Carbon::create($year, 1, 1)->startOfDay();
        $yearEnd = Carbon::create($year, 12, 31)->endOfDay();

        $approvedLeaves = Leave::query()
            ->where('employee_id', $employeeId)
            ->where('leave_type', $leaveType)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $yearEnd)
            ->whereDate('end_date', '>=', $yearStart);

        if ($excludeLeaveId !== null) {
            $approvedLeaves->whereKeyNot($excludeLeaveId);
        }

        $usedDays = $approvedLeaves
            ->get(['start_date', 'end_date'])
            ->sum(function (Leave $leave) use ($yearStart, $yearEnd): int {
                $startDate = Carbon::parse($leave->start_date)->max($yearStart);
                $endDate = Carbon::parse($leave->end_date)->min($yearEnd);

                return $startDate->diffInDays($endDate) + 1;
            });

        $maxDays = SystemConfiguration::getLeaveCount($leaveType);

        return max(0, $maxDays - $usedDays);
    }
}
