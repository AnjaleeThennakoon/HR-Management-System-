<?php

namespace App\UseCases\Report\Request;

use Illuminate\Foundation\Http\FormRequest;

class LateHoursReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start' => ['nullable', 'date_format:Y-m-d'],
            'end'   => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start'],

        ];
    }
}
