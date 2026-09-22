<?php

namespace App\UseCases\Designation;

use App\Models\Designation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListDesignationInteractors
{
    public function execute(?string $search = null, ?int $perPage = 10): LengthAwarePaginator
    {
        return Designation::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('level')
            ->orderBy('name')
            ->paginate($perPage);
    }
}
