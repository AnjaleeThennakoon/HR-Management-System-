<?php

namespace App\UseCases\Leave\Request\Validators;

use App\Models\Leave;
use App\Models\Support\LeaveSupport;
use App\UseCases\Leave\Request\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

class LeaveValidator
{
    public function __construct(
        protected LeaveRequest $request
    ) {}

    public function __invoke(Validator $validator): void
    {
        if ($validator->errors()->hasAny([
            'employee_id',
            'leave_type',
            'start_date',
            'end_date',
        ])) {
            return;
        }

        $this->validateOverlap($validator);
        $this->validateBalance($validator);
    }

    protected function validateOverlap(Validator $validator): void
    {
        $employeeId = (int) $this->request->input('employee_id');
        $startDate = Carbon::parse($this->request->input('start_date'));
        $endDate = Carbon::parse($this->request->input('end_date'));
        $leaveId = $this->request->route('id') ?? $this->request->route('leave')?->id;

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
    }

    protected function validateBalance(Validator $validator): void
    {
        $employeeId = (int) $this->request->input('employee_id');
        $leaveType = $this->request->input('leave_type');
        $startDate = Carbon::parse($this->request->input('start_date'));
        $endDate = Carbon::parse($this->request->input('end_date'));
        $leaveId = $this->request->route('id') ?? $this->request->route('leave')?->id;

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
    }
}
