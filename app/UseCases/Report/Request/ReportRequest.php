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
            'employee_name' => ['nullable', 'string'],
            'month' => ['nullable', 'string'],
            'year' => ['nullable', 'string'],
            'id' => ['nullable', 'integer'],
            'work_hours' => ['integer', 'nullable'],
        ];
    }
}
