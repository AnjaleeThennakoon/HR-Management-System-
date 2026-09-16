<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            'Human Resources',
            'Information Technology',
            'Finance',
            'Marketing',
            'Sales',
            'Operations',
            'Customer Service',
            'Research & Development',
            'Legal',
            'Administration',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($departments),
        ];
    }
}
