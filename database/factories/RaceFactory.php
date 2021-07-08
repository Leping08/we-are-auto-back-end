<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Race;
use App\Models\Season;
use App\Models\Series;
use App\Models\Track;

class RaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Race::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'length' => $this->faker->sentence,
            'series_id' => Series::factory(),
            'track_id' => Track::factory(),
            'season_id' => Season::factory(),
            'starts_at' => $this->faker->dateTime(),
            'finishes_at' => $this->faker->dateTime(),
        ];
    }
}
