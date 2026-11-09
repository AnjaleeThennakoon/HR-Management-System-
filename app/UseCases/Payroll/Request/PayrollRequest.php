<?php

namespace App\UseCases\Payroll\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'month' => [
                'required',
                'integer',
                'between:1,12',
                Rule::unique('payrolls', 'month')
                    ->where('employee_id', $this->input('employee_id'))
                    ->where('year', $this->input('year')),
            ],
            'year' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'month.unique' => 'Payroll for this month has already been generated.',
        ];
    }
}
