<?php

namespace Database\Factories;

use App\Models\CarClass;
use App\Models\Video;
use App\Models\VideoPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\Series;

class VideoPlatformFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VideoPlatform::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
