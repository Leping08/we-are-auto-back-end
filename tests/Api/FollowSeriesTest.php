<?php


namespace Tests\Api;

use App\Models\FollowSeries;
use App\Models\Race;
use App\Models\RaceSuggestion;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Tests\TestCase;

class FollowSeriesTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_authenticated_user_can_follow_a_series()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $series = Series::factory()->create();

        $data = [
            'series_id' => $series->id,
            'user_id' => $user->id
        ];

        $this->assertCount(0, FollowSeries::all());

        $this->post(route('follow.series.store'), $data)
            ->assertStatus(201);

        $this->assertCount(1, FollowSeries::all());

        $user->refresh();
        $series->refresh();

        $this->assertTrue($user->series_following->contains($series));
        $this->assertTrue($series->users_following->contains($user));
    }

    /** @test */
    public function a_authenticated_user_can_unfollow_a_series()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $series = Series::factory()->create();

        $data = [
            'series_id' => $series->id,
            'user_id' => $user->id
        ];

        $this->assertCount(0, FollowSeries::all());

        $this->post(route('follow.series.store'), $data)
            ->assertStatus(201);

        $this->assertCount(1, FollowSeries::all());

        $this->post(route('follow.series.store'), $data)
            ->assertStatus(201);

        $this->assertCount(0, FollowSeries::all());

        $user->refresh();
        $series->refresh();

        $this->assertFalse($user->series_following->contains($series));
        $this->assertFalse($series->users_following->contains($user));
    }

    /** @test */
    public function a_user_can_not_follow_a_series_for_a_different_user()
    {
        $user_1 = User::factory()->create();
        Passport::actingAs($user_1);

        $user_2 = User::factory()->create();

        $series = Series::factory()->create();

        $data = [
            'series_id' => $series->id,
            'user_id' => $user_2->id
        ];

        $this->assertCount(0, FollowSeries::all());

        $this->post(route('follow.series.store'), $data)
            ->assertStatus(403);

        $this->assertCount(0, FollowSeries::all());
    }

    /** @test */
    public function a_authenticated_user_check_if_they_follow_a_series()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $series_1 = Series::factory()->create();
        $series_2 = Series::factory()->create();

        $data = [
            'series_id' => $series_1->id,
            'user_id' => $user->id
        ];

        $this->assertCount(0, FollowSeries::all());

        $this->post(route('follow.series.store'), $data)
            ->assertStatus(201);

        $this->assertCount(1, FollowSeries::all());

        $this->get(route('follow.series.show', ['series' => $series_1]))
            ->assertStatus(200)
            ->assertJson([
                'follow' => true
            ]);

        $this->get(route('follow.series.show', ['series' => $series_2]))
            ->assertStatus(200)
            ->assertJson([
                'follow' => false
            ]);
    }
}
