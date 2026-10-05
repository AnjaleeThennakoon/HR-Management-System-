<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListAttendanceInteractors
{
    public function execute(
        ?string $search = null,
        ?int $perPage = null
    ): LengthAwarePaginator|Collection {
        $query = Attendance::with('employee'); // eager load to avoid N+1

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('date', 'like', '%'.$search.'%')->orWhereHas('employee', function ($q) use ($search) {
                    $q->where('first_name', 'like', '%'.$search.'%')->orWhere('last_name', 'like', '%'.$search.'%');
                });
            });
        }

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }
}
