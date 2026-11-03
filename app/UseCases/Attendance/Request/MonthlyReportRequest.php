<?php

namespace App\UseCases\Attendance\Request;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyReportRequest extends FormRequest
{
    protected bool $isInitialVisit = false;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'min:1900', 'max:'.now()->year],
            'month' => ['required', 'integer', 'between:1,12'],
            'employee_id' => $this->isInitialVisit
                ? ['nullable', 'integer', 'exists:employees,id']
                : ['required', 'integer', 'exists:employees,id'],
            'total_late_minutes' => ['sometimes', 'integer', 'min:0'],
            'total_ot_minutes' => ['sometimes', 'integer', 'min:0'],
            'total_no_pay_days' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
