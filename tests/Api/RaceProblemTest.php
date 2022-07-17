<?php


namespace Tests\Api;

use App\Models\FollowSeries;
use App\Models\Race;
use App\Models\RaceProblem;
use App\Models\RaceSuggestion;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RaceProblemTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_non_authenticated_user_can_store_a_race_problem()
    {
        $data = [
            'race_id' => Race::factory()->create()->id,
            'description' => $this->faker()->sentence()
        ];

        $this->post(route('race-problem.store'), $data)
            ->assertStatus(201);

        $this->assertEquals(1, RaceProblem::all()->count());
        $this->assertEquals(null, RaceProblem::first()->user_id);
    }

    /** @test */
    public function an_authenticated_user_can_store_a_race_problem()
    {
        $user = User::factory()->create();
        $race = Race::factory()->create();

        $data = [
            'user_id' => $user->id,
            'race_id' => $race->id,
            'description' => $this->faker()->sentence()
        ];

        $this->post(route('race-problem.store'), $data)
            ->assertStatus(201);

        $this->assertEquals(1, RaceProblem::all()->count());
        $this->assertEquals($user->id, RaceProblem::first()->user_id);
    }
}
