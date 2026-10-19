<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveApproval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveApproval>
 */
class LeaveApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'leave_id' => Leave::factory(),
            'employee_id' => Employee::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'remarks' => $this->faker->sentence(),
            'approved_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
