<?php

namespace Database\Factories;

use App\Models\Series;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeriesTag>
 */
class SeriesTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'series_id' => Series::factory(),
            'tag_id' => Tag::factory(),
        ];
    }
}
