<?php

namespace App\UseCases\LeaveApproval\Request;

use Illuminate\Foundation\Http\FormRequest;

class LeaveApprovalRequest extends FormRequest
{
    public  function authorize(): bool
    {
        return true;
    }

    public  function rules(): array
    {
        return [
            'leave_id' => ['required','integer','exists:leaves,id'],
            'employee_id' => ['required','integer','exists:employees,id'],
            'status' => ['required','in:pending,approved,rejected'],
            'remarks' => ['string'],
            'approved_at'=>['required','date'],
        ];
    }

}
