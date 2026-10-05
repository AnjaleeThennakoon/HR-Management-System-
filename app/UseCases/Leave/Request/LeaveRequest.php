<?php

namespace App\UseCases\Leave\Request;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('holiday') ?? $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:holidays,name,'.$id],
            'date' => ['required', 'date'],
            'reason' => ['required', 'string'],
            'type' => ['required', 'string', Rule::in(['Annual', 'medical'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'status' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'employee_id' => ['required', 'integer'],

        ];
    }
}
