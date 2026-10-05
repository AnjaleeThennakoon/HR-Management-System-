<?php

namespace App\UseCases\Leave;

use App\Models\Leave;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListLeaveInteractors
{
    public function execute(?string $search = null, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = Leave::with('employee');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('leave_type', 'like', "%{$search}%")
                    ->orWhere('start_date', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }
}
