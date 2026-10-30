<?php

namespace App\UseCases\Employee\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('shift_in') && is_string($this->shift_in) && strlen($this->shift_in) > 5) {
            $this->merge(['shift_in' => substr($this->shift_in, 0, 5)]);
        }
        if ($this->has('shift_out') && is_string($this->shift_out) && strlen($this->shift_out) > 5) {
            $this->merge(['shift_out' => substr($this->shift_out, 0, 5)]);
        }
        if ($this->has('date_of_birth') && is_string($this->date_of_birth) && strlen($this->date_of_birth) > 10) {
            $this->merge(['date_of_birth' => substr($this->date_of_birth, 0, 10)]);
        }
    }

    public function rules(): array
    {
        $employeeId = $this->route('id');
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'employee_id' => $isUpdate
                ? ['sometimes', 'string', 'max:50', Rule::unique('employees', 'employee_id')->ignore($employeeId)]
                : ['required', 'string', 'max:50', 'unique:employees,employee_id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'designation_id' => ['nullable', 'integer', 'exists:designations,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', Rule::in(['Male', 'Female'])],
            'nic' => $isUpdate
                ? ['sometimes', 'regex:/^(?:\d{12}|\d{10}v)$/i', Rule::unique('employees', 'nic')->ignore($employeeId)]
                : ['required', 'regex:/^(?:\d{12}|\d{10}v)$/i', 'unique:employees,nic'],
            'phone' => ['required', 'digits:10'],
            'address' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'min:15000', 'max:350000'],
            'shift_in' => ['required', 'date_format:H:i'],
            'shift_out' => ['required', 'date_format:H:i', 'after:shift_in'],
        ];
    }
}
