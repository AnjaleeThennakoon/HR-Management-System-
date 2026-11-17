<?php

namespace App\UseCases\User;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListUserInteractor
{
    public function execute(
        ?string $search = null,
        ?int $perPage = null
    ): LengthAwarePaginator|Collection {
        $query = User::with('roles')
            ->whereDoesntHave('roles', function ($roleQuery) {
                $roleQuery->where('name', 'admin');
            })
            ->where('role', '!=', 'admin')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->paginate(10);
    }
}
