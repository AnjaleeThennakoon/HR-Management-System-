<?php

namespace App\UseCases\LeaveApproval;

use App\Models\LeaveApproval;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListLeaveApprovalInteractors
{
    public function execute(?string $search = null, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = LeaveApproval::with(['leave.employee', 'employee']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%")

                    ->orWhereHas('leave', function ($qLeave) use ($search) {
                        $qLeave->where('leave_type', 'like', "%{$search}%")
                            ->orWhere('start_date', 'like', "%{$search}%")

                            ->orWhereHas('employee', function ($qEmp) use ($search) {
                                $qEmp->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }
}
