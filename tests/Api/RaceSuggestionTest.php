<?php


namespace Tests\Api;


use App\Models\Race;
use App\Models\RaceSuggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RaceSuggestionTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_user_must_be_authenticated_to_add_a_race_suggestion()
    {
        $race = Race::factory()->create();

        $data = [
            'race_id' => $race->id,
            'data' => [
                [
                    'part' => 1,
                    'link' => 'https://youtube.com/zzzzzz'
                ],
                [
                    'part' => 2,
                    'link' => 'https://youtube.com/fffffff'
                ]
            ]
        ];

        $this->assertCount(0, RaceSuggestion::all());

        $this->post(route('race.suggestion.store'), $data)
            ->assertStatus(302);

        $this->assertCount(0, RaceSuggestion::all());
    }

    /** @test */
    public function a_user_can_add_a_race_suggestion_with_valid_data()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $race = Race::factory()->create();

        $data = [
            'race_id' => $race->id,
            'data' => [
                [
                    'part' => 1,
                    'link' => 'https://youtube.com/zzzzzz'
                ],
                [
                    'part' => 2,
                    'link' => 'https://youtube.com/fffffff'
                ]
            ]
        ];

        $this->assertCount(0, RaceSuggestion::all());

        $this->post(route('race.suggestion.store'), $data)
            ->assertStatus(201);

        $this->assertCount(1, RaceSuggestion::all());
    }
}