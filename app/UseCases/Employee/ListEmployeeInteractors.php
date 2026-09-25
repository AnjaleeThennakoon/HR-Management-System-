<?php

namespace App\UseCases\Employee;

use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListEmployeeInteractors
{
    public function execute(
        ?string $search = null,
        ?int $perPage = null
    ): LengthAwarePaginator|Collection {

        $query = Employee::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
