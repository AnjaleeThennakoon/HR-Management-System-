<?php

namespace App\UseCases\Department;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ListDepartmentInteractors
{
    public function execute(?string $search = null, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = Department::query();

        if ($search) {
            $query->where('department', 'like', "%{$search}%");
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }


}
