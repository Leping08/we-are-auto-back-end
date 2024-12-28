<?php

namespace Tests\Api;

use App\Models\Race;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RaceRatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_cannot_rate_races()
    {
        $race = Race::factory()->create();
        
        $response = $this->postJson(route('race.rate'), [
            'race_id' => $race->id,
            'rating' => 5
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function a_user_can_rate_a_race()
    {
        $user = User::factory()->create();
        $race = Race::factory()->create();

        $this->actingAs($user, 'api')->postJson(route('race.rate'), [
            'race_id' => $race->id,
            'rating' => 5
        ])->assertStatus(201);

        $this->assertEquals(5, $race->fresh()->averageRating());

        // Test updating via the same endpoint
        $this->actingAs($user, 'api')->postJson(route('race.rate'), [
            'race_id' => $race->id,
            'rating' => 3
        ])->assertStatus(201);

        $this->assertEquals(3, $race->fresh()->averageRating());
    }

    // Remove the a_user_can_update_their_rating test as it's now covered above

    /** @test */
    public function a_user_can_only_rate_between_1_and_5()
    {
        $user = User::factory()->create();
        $race = Race::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson(route('race.rate', [
            'race_id' => $race->id,
            'rating' => 6
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rating']);

        $response = $this->actingAs($user, 'api')->postJson(route('race.rate', [
            'race_id' => $race->id,
            'rating' => 0
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rating']);
    }

    /** @test */
    public function a_race_has_an_average_rating()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $race = Race::factory()->create();

        $this->actingAs($user1, 'api')->postJson(route('race.rate', [
            'race_id' => $race->id,
            'rating' => 4
        ]));

        $this->actingAs($user2, 'api')->postJson(route('race.rate', [
            'race_id' => $race->id,
            'rating' => 2
        ]));

        $this->assertEquals(3, $race->fresh()->averageRating());
    }

    /** @test */
    public function rating_cache_is_managed_properly_through_api_endpoints()
    {
        $user = User::factory()->create();
        $race = Race::factory()->create();
        $cacheKey = "race.{$race->id}.average_rating";

        $this->actingAs($user, 'api');

        // Verify cache doesn't exist initially
        $this->assertFalse(Cache::has($cacheKey));

        // Create rating via API
        $this->postJson(route('race.rate'), [
            'race_id' => $race->id,
            'rating' => 4
        ])->assertStatus(201);

        // Make the api call to get the race to trigger the cache
        $this->getJson(route('races.show', $race))
            ->assertOk();

        $this->assertTrue(Cache::has($cacheKey));
        $this->assertEquals(4, Cache::get($cacheKey));

        // Update rating via same endpoint
        $this->postJson(route('race.rate'), [
            'race_id' => $race->id,
            'rating' => 5
        ])->assertStatus(201);

        $this->getJson(route('races.show', $race))
            ->assertOk();

        $this->assertTrue(Cache::has($cacheKey));
        $this->assertEquals(5, Cache::get($cacheKey));

        // Add second user rating to test average
        $user2 = User::factory()->create();
        $this->actingAs($user2, 'api')
            ->postJson(route('race.rate'), [
                'race_id' => $race->id,
                'rating' => 3
            ]);

        $this->getJson(route('races.show', $race))
            ->assertOk();

        $this->assertTrue(Cache::has($cacheKey));
        $this->assertEquals(4, Cache::get($cacheKey));
    }
}
