<?php

namespace App\UseCases\Report\Request;

use Illuminate\Foundation\Http\FormRequest;

class DateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date_format:Y-m-d'],
            'month' => ['nullable', 'date_format:Y-m'],
            'employee_name' => ['nullable', 'string'],
            'year'        => ['nullable', 'digits:4'],

        ];
    }
}
