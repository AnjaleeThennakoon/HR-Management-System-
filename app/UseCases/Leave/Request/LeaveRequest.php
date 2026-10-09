<?php

namespace App\UseCases\Leave\Request;

use App\Models\Leave;
use App\Models\Support\LeaveSupport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
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
            'leave_type' => ['required', 'string', Rule::in(['Annual', 'Medical', 'casual'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
        ];
    }

    public function after(): array
    {
        return [
            new LeaveValidator($this),
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type.in' => 'Leave type must be Annual, Medical, or Casual.',

            'end_date.after_or_equal' => 'End date must be on or after start date.',

            'reason.required' => 'Please provide a reason for your leave.',
        ];
    }
}
