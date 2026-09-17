<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    private const DEPARTMENTS = [
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

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(self::DEPARTMENTS),
        ];
    }

    public function withoutFinance(): static
    {
        return $this->state([
            'name' => $this->faker->unique()->randomElement(
                array_diff(self::DEPARTMENTS, ['Finance'])
            ),
        ]);
    }
}
