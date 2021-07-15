<?php


namespace Tests\Api;


use App\Models\Race;
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

        $this->get(route('races.show', ['race' => $race]))
            ->assertStatus(200)
            ->assertSee($race['name'])
            ->assertSee($race['length']);
    }
}