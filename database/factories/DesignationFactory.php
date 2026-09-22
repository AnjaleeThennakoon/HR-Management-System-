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
        'Senior Software Engineer',
        'Software Engineer',
        'Junior Software Engineer',
        'Software Engineering Intern',
    ];

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(self::DEFINITIONS),
        ];
    }
}
