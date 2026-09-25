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
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'nic' => $this->faker->unique()->randomElement([
                $this->faker->numerify('##########').'v',
                $this->faker->numerify('############'),
            ]),
            'phone' => $this->faker->numerify('##########'),
            'address' => $this->faker->address(),
            'department_id' => Department::factory(),
            'designation_id' => Designation::factory(),
        ];
    }
}
