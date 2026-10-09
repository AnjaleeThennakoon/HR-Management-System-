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
            function (Validator $validator): void {
                if ($validator->errors()->hasAny([
                    'employee_id',
                    'leave_type',
                    'start_date',
                    'end_date',
                ])) {
                    return;
                }

                $employeeId = (int) $this->input('employee_id');
                $leaveType = $this->input('leave_type');
                $startDate = Carbon::parse($this->input('start_date'));
                $endDate = Carbon::parse($this->input('end_date'));
                $leaveId = $this->route('id')
                    ?? $this->route('leave')?->id;

                $overlapQuery = Leave::query()
                    ->where('employee_id', $employeeId)
                    ->whereDate('start_date', '<=', $endDate->toDateString())
                    ->whereDate('end_date', '>=', $startDate->toDateString());

                if ($leaveId !== null) {
                    $overlapQuery->where('id', '!=', $leaveId);
                }

                if ($overlapQuery->exists()) {
                    $validator->errors()->add(
                        'leave_type',
                        'The employee already has a leave request that overlaps these dates.'
                    );
                }

                $requestedDays = $startDate->diffInDays($endDate) + 1;
                $remainingDays = LeaveSupport::getRemainingDays(
                    $employeeId,
                    $leaveType,
                    excludeLeaveId: $leaveId
                );

                if ($requestedDays > $remainingDays) {
                    $validator->errors()->add(
                        'leave_type',
                        "Insufficient leave balance. You have {$remainingDays} day(s) remaining, but requested {$requestedDays} day(s)."
                    );
                }
            },
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
