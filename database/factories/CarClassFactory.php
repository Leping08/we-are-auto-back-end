<?php

namespace Database\Factories;

use App\Models\CarClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'image' => $this->faker->word
        ];
    }
}
