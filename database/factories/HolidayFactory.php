<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Holiday;

class HolidayFactory extends Factory
{
    protected $model = Holiday::class;

    public function definition():array
    {
     return [
         'name' => $this->faker->word(),
         'date' => $this->faker->date(),
         'type' => $this->faker->randomElement(['public', 'special'])


     ];
    }

}
