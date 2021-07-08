<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\Race;
use App\Models\Result;

class ResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start' => $this->faker->numberBetween(1,40),
            'position' => $this->faker->numberBetween(1,40),
            'car_id' => Car::factory(),
            'race_id' => Race::factory(),
        ];
    }
}
