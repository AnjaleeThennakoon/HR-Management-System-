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

        $query = Attendance::query();
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('date', 'like', '%'.$search.'%');
            });
        }
        if ($perPage) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }

    }
}
