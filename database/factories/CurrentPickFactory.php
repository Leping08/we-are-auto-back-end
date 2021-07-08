<?php


namespace Database\Factories;


use App\Models\Car;
use App\Models\CurrentPick;
use App\Models\League;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrentPickFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CurrentPick::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'league_id' => League::factory(),
            'car_id' => Car::factory(),
            'user_id' => User::factory(),
        ];
    }
}