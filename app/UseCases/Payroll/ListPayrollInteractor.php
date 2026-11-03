<?php

namespace App\UseCases\Payroll;

use App\Models\Payroll;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPayrollInteractor
{
    public function execute(?string $search = null, ?int $perPage = 10): LengthAwarePaginator
    {
        $query = Payroll::query()
            ->with(['employee.department', 'employee.designation']);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('month', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
