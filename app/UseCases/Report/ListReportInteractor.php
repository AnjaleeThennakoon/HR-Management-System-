<?php

namespace App\UseCases\Report;

use App\Models\Report;

class ListReportInteractor
{
    public function execute(?string $search = null, ?int $perPage = 10)
    {
        $query = Report::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('month', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
