<?php

namespace App\UseCases\Holiday;

use App\Models\Holiday;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListHolidayInteractors
{
    public function execute(?string $search = null, ?int $perPage = 10): LengthAwarePaginator|Collection
    {
        $query = Holiday::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }
}
