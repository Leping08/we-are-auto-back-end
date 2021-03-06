<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Series;

class SeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Series::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'full_name' => $this->faker->word,
            'logo' => $this->faker->imageUrl,
            'image_url' => $this->faker->imageUrl,
            'description' => $this->faker->paragraph,
        ];
    }
}
