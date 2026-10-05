<?php

namespace App\UseCases\Attendance;

use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreAttendanceInteractors
{
    public function execute(array $attendance): Attendance
    {
        $validate = $this->validate($attendance);

        return Attendance::create($attendance);
    }

    private function validate(array $attendance): array
    {
        $validator = Validator::make($attendance, [
            'employee_id' => [
                'required',
                'integer',
                'exists:employees,id',
            ],
            'date' => [
                'required',
                'date',
            ],
            'in_time' => [
                'required',
                'date_format:H:i',
            ],
            'out_time' => [
                'nullable',
                'date_format:H:i',
            ],
        ], [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'date.required' => 'Date is required.',
            'in_time.required' => 'In time is required.',
            'in_time.date_format' => 'In time must be in HH:MM format.',
            'out_time.date_format' => 'Out time must be in HH:MM format.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();

    }
}
