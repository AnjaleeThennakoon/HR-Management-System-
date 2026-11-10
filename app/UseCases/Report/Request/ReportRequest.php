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
            'search' => ['nullable', 'string', 'max:255'],
            'employee_name' => ['nullable', 'string'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],

        ];
    }
}
