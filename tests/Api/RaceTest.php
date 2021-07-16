<?php


namespace Tests\Api;


use App\Models\Race;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_user_can_get_all_races()
    {
        Race::factory()->count(3)->create();

        $this->get(route('races.index'))
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function a_user_can_get_a_race_by_id()
    {
        $race = Race::factory()->create();
        $video = Video::factory()->create([
            'race_id' => $race
        ]);

        $this->get(route('races.show', ['race' => $race]))
            ->assertStatus(200)
            ->assertSee($race['name'])
            ->assertSee($race['length'])
            ->assertSee(['videos', 'series', 'season', 'platform', 'track'])
            ->assertSee($video['video_id']);
    }
}