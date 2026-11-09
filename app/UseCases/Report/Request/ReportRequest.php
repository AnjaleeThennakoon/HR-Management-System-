<?php

namespace App\UseCases\Report\Request;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:255'],
            'employee_name' => ['nullable', 'string'],
            'year'        => ['nullable', 'digits:4'],
            'per_page'    => ['nullable', 'integer', 'min:1', 'max:100'],

            'id' => ['nullable', 'integer'],
            'work_hours'  => ['nullable', 'integer'],
        ];
    }
}
