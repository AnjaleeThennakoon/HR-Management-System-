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

    public function rules(): array
    {
        return [
            'employee_id' => [
                'required',
                'string',
                'max:50',
                'unique:employees,employee_id,'.$this->id,
            ],

            'department_id' => [
                'required',
                'integer',
                'exists:departments,id',
            ],

            'designation_id' => [
                'required',
                'integer',
                'exists:designations,id',
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'date_of_birth' => [
                'required',
                'date',
            ],

            'gender' => [
                'required',
                'string',
                Rule::in(['Male', 'Female']),
            ],

            'nic' => [
                'required',
                'string',
                'max:20',
                'unique:employees,nic,' . $this->id,
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
