<?php

namespace Database\Factories;

use App\Models\Video;
use App\Models\VideoProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoProgressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VideoProgress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'video_id' => Video::factory(),
            'percentage' => $this->faker->numberBetween(1,100),
            'seconds' => $this->faker->numberBetween(1,100)
        ];
    }
}
