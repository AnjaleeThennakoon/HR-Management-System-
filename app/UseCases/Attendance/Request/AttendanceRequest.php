<?php

namespace App\UseCases\Attendance\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attendanceId = $this->route('id');
        $date = $this->input('date');

        return [
            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
                Rule::unique('attendances', 'employee_id')
                    ->where('date', $date)
                    ->ignore($attendanceId),
            ],
            'date' => ['required', 'date'],
            'in_time' => ['nullable', 'date_format:H:i', 'before:out_time'],
            'out_time' => ['nullable', 'date_format:H:i', 'after:in_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.unique' => 'This employee already has an attendance record for this date.',
        ];
    }
}
