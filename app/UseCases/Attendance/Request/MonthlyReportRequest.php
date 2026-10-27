<?php

namespace App\UseCases\Attendance\Request;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class MonthlyReportRequest extends FormRequest
{
    protected bool $isInitialVisit = false;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->isInitialVisit = ! $this->has('month') && ! $this->has('year') && ! $this->has('employee_id');

        $month = $this->input('month');

        if (is_string($month) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) === 1) {
            [$year, $month] = explode('-', $month);

            $this->merge([
                'year' => $year,
                'month' => $month,
            ]);
        }

        if ($this->isInitialVisit) {
            $firstEmployee = Employee::first();
            $this->merge([
                'year' => now()->year,
                'month' => now()->month,
                'employee_id' => $firstEmployee?->id,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'min:1900', 'max:'.now()->year],
            'month' => ['required', 'integer', 'between:1,12'],
            'employee_id' => $this->isInitialVisit
                ? ['nullable', 'integer', 'exists:employees,id']
                : ['required', 'integer', 'exists:employees,id'],
        ];
    }
}
