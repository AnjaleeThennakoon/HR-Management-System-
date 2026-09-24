<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'employee_id' => $this->faker->unique()->bothify('EMP####'),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'nic' => $this->faker->unique()->numerify('############'),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'department_id' => Department::factory(),
            'designation_id' => Designation::factory(),
        ];
    }
}
