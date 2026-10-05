<?php

namespace App\UseCases\Leave\Request;

use App\Models\Leave;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class LeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'leave_type' => [
                'required',
                'string',
                Rule::in(['Annual', 'Medical', 'casual']),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['employee_id', 'start_date', 'end_date'])) {
                    return;
                }

                $query = Leave::query()
                    ->where('employee_id', $this->input('employee_id'))
                    ->where('start_date', '<=', $this->input('end_date'))
                    ->where('end_date', '>=', $this->input('start_date'));

                $leaveId = $this->route('id');

                if ($leaveId !== null) {
                    $query->where('id', '!=', $leaveId);
                }

                if ($query->exists()) {
                    $validator->errors()->add(
                        'leave_type',
                        'The employee already has a leave request that overlaps these dates.'
                    );
                }
            },
        ];
    }
}
