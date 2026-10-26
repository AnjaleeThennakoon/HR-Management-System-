<?php

namespace App\UseCases\Attendance\Request;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year'        => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'month'       => ['required', 'integer', 'between:1,12'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ];
    }
}
