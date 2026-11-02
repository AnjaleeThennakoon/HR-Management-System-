<?php

namespace App\UseCases\Attendance\Request;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCsvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'csv_file' => [
                'required',
                'file',
                'mimes:csv',
            ],
        ];
    }
}
