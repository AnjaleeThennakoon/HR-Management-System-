<?php

namespace App\UseCases\Report;

use App\Models\Report;

class ListReportInteractor
{
    public function execute(?string $search = null, int $perPage = 10)
    {
        return Report::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('employee_name', 'like', "%{$search}%")
                        ->orWhere('month', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage);
    }
}
