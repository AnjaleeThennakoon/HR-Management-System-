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
            'year' => ['nullable', 'integer'],
            'month' => ['nullable'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
        ];
    }
}
