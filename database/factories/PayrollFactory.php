<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\MonthlyAttendanceFinalize;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payroll>
 */
class PayrollFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'year' => $this->faker->year(),
            'month' => $this->faker->month(),
            'basic_salary' => $this->faker->numberBetween(30000, 100000),
            'allowances' => $this->faker->numberBetween(0, 5000),
            'deductions' => $this->faker->numberBetween(0, 3000),
            'net_salary' => $this->faker->numberBetween(25000, 95000),
        ];
    }
}
