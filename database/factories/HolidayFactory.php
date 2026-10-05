<?php

namespace Database\Factories;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Factories\Factory;

class HolidayFactory extends Factory
{
    protected $model = Leave::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['public', 'special']),

        ];
    }
}
