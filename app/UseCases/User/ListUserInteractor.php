<?php

namespace App\UseCases\User;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListUserInteractor
{
    public function execute(?string $search = null, ?int $perPage = null): LengthAwarePaginator|Collection
    {
        $query = User::with(['roles', 'employee'])
            ->whereDoesntHave('roles', function ($roleQuery) {
                $roleQuery->where('name', 'admin');
            })
            ->where('role', '!=', 'admin')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->paginate(10);
    }
}
