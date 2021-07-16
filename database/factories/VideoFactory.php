<?php

namespace Database\Factories;

use App\Models\CarClass;
use App\Models\Video;
use App\Models\VideoPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Car;
use App\Models\Series;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'video_id' => $this->faker->lexify('????????'),
            'video_platform_id' => VideoPlatform::factory(),
            'start_time' => $this->faker->numberBetween(1,100),
            'race_id' => VideoPlatform::factory(),
        ];
    }
}
