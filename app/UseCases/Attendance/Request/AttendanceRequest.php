<?php

namespace App\UseCases\Attendance\Request;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'in_time' => ['required', 'date_format:H:i'],
            'out_time' => ['nullable', 'date_format:H:i'],
        ];
    }
}
