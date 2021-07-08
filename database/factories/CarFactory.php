<?php

namespace Database\Factories;

use App\Models\CarClass;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\Series;

class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->word,
            'series_id' => Series::factory(),
            'car_class_id' => CarClass::factory(),
            'image' => $this->faker->imageUrl()
        ];
    }
}
