<?php

namespace Database\Factories;

use App\Models\user;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Leave>
 */
class LeaveFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => user::factory(),
            'leave_type' => $this->faker->randomElement(['Annual', 'Medical', 'casual']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'remarks' => $this->faker->sentence(),
            'approved_at' => $this->faker->optional()->dateTime(),
            'user_id' => User::factory(),
        ];
    }
}
