<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\MonthlyAttendanceFinalized;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthlyAttendanceFinalizedFactory extends Factory
{
    protected $model = MonthlyAttendanceFinalized::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2020, 2026),
            'total_late_minutes' => $this->faker->numberBetween(0, 120),
            'total_ot_minutes' => $this->faker->numberBetween(0, 300),
            'total_no_pay_days' => $this->faker->numberBetween(0, 5),
            'finalized_by' => User::factory(),
            'finalized_at' => now(),

        ];
    }
}
