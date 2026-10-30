<?php

namespace App\UseCases\Payroll\Request;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'month' => ['required', 'integer'],
            'year' => ['required', 'integer'],
        ];
    }
}
