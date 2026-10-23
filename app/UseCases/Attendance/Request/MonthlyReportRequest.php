<?php

namespace App\UseCases\Attendance\Request;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $month = $this->input('month');

        if (is_string($month) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) === 1) {
            [$year, $month] = explode('-', $month);

            $this->merge([
                'year'  => $year,
                'month' => $month,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'year'        => ['required', 'integer'],
            'month'       => ['required', 'integer', 'between:1,12'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ];
    }
}
