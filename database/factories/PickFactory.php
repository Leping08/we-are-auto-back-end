<?php

namespace Database\Factories;

use App\Models\League;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\Pick;
use App\Models\Race;
use App\Models\User;

class PickFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pick::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'race_id' => Race::factory(),
            'car_id' => Car::factory(),
            'user_id' => User::factory(),
            'league_id' => League::factory(),
        ];
    }
}
