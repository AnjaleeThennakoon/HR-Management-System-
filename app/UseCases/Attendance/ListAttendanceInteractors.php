<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceImport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListAttendanceInteractors
{
    public function execute(
        ?string $search = null,
        ?int $perPage = null,
        ?int $importId = null,
        ?int $userEmployeeId = null
    ): LengthAwarePaginator|Collection {
        $query = Attendance::with('employee');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('date', 'like', '%'.$search.'%')
                    ->orWhereHas('employee', function ($q) use ($search) {
                        $q->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%');
                    });
            });
        }

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }

    public function getImportStatus(?int $importId, ?int $userEmployeeId = null): ?AttendanceImport
    {
        if (! $importId || $importId <= 0) {
            return null;
        }

        return AttendanceImport::query()
            ->when($userEmployeeId, fn ($q, $empId) => $q->where('employee_id', $empId))
            ->findOrFail($importId);
    }
}
