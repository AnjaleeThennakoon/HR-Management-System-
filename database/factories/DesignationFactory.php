<?php

namespace Database\Factories;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignationFactory extends Factory
{
    protected $model = Designation::class;
    private const DEFINITIONS = [
        'Chief Executive Officer',
        'Chief Technology Officer',
        'General Manager',
        'Human Resources Manager',
        'Finance Manager',
        'Project Manager',
        'Software Engineer',
        'Senior Software Engineer',
        'Junior Software Engineer',
        'Software Engineering Intern',
        'QA Engineer',
        'Senior QA Engineer',
        'UI/UX Designer',
        'Business Analyst',
        'System Analyst',
        'DevOps Engineer',
        'Database Administrator',
        'Network Engineer',
        'IT Support Executive',
        'Marketing Executive',
        'Sales Executive',
        'Accountant',
        'HR Executive',
        'Administrative Officer',
        'Customer Service Executive',
    ];
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(self::DEFINITIONS),
        ];
    }
}
