<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\leave;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<leave>
 */
class LeaveFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'leave_type' => $this->faker->randomElement(['Annual', 'Medical', 'casual']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
