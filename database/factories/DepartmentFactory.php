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
        static $index = 0;
        $name = self::DEPARTMENTS[$index % count(self::DEPARTMENTS)].($index >= count(self::DEPARTMENTS) ? ' '.($index + 1) : '');
        $index++;

        return [
            'name' => $name,
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
