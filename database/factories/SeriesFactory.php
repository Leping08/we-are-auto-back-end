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
            'name' => $this->faker->name,
            'full_name' => $this->faker->name,
            'logo' => $this->faker->imageUrl,
            'logo_file' => $this->faker->uuid,
            'image_url' => $this->faker->imageUrl,
            'image_file' => $this->faker->uuid,
            'website' => $this->faker->url,
            'description' => $this->faker->paragraph,
            'settings' => null,
        ];
    }
}
