<?php

namespace Tests\Unit;

use App\Jobs\SendFollowedSeriesEmails;
use App\Mail\FollowedSeries;
use App\Models\FollowSeries;
use App\Models\Race;
use App\Models\Series;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;
use Tests\TestCase;

class FollowedSeriesEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_email_send_if_the_series_has_races_with_videos()
    {
        Mail::fake();
        $user_1 = User::factory()->create();
        $user_2 = User::factory()->create();
        Passport::actingAs($user_1);

        $series_1 = Series::factory()->create();
        $race_1 = Race::factory()->create([
            'series_id' => $series_1->id
        ]);
        $video_1 = Video::factory()->create([
            'race_id' => $race_1->id
        ]);

        $series_2 = Series::factory()->create();
        $race_2 = Race::factory()->create([
            'series_id' => $series_2->id
        ]);
        $video_2 = Video::factory()->create([
            'race_id' => $race_2->id
        ]);

        $data = [
            'series_id' => $series_1->id,
            'user_id' => $user_1->id
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

        Mail::assertNothingSent();

        SendFollowedSeriesEmails::dispatchSync();

        Mail::assertQueued(FollowedSeries::class, 1);
    }

    /** @test */
    public function the_email_will_not_send_if_the_series_has_no_races()
    {
        Mail::fake();
        $user_1 = User::factory()->create();
        // $user_2 = User::factory()->create();
        Passport::actingAs($user_1);

        $series_1 = Series::factory()->create();
        // $race_1 = Race::factory()->create([
        //     'series_id' => $series_1->id
        // ]);
        // $video_1 = Video::factory()->create([
        //     'race_id' => $race_1->id
        // ]);

        // $series_2 = Series::factory()->create();
        // $race_2 = Race::factory()->create([
        //     'series_id' => $series_2->id
        // ]);
        // $video_2 = Video::factory()->create([
        //     'race_id' => $race_2->id
        // ]);

        $data = [
            'series_id' => $series_1->id,
            'user_id' => $user_1->id
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

        // $this->get(route('follow.series.show', ['series' => $series_2]))
        //     ->assertStatus(200)
        //     ->assertJson([
        //         'follow' => false
        //     ]);

        Mail::assertNothingSent();

        SendFollowedSeriesEmails::dispatchSync();

        Mail::assertNothingQueued();
    }

    /** @test */
    public function the_email_will_not_send_if_the_series_has_races_but_they_have_no_videos()
    {
        Mail::fake();
        $user_1 = User::factory()->create();
        // $user_2 = User::factory()->create();
        Passport::actingAs($user_1);

        $series_1 = Series::factory()->create();
        $race_1 = Race::factory()->create([
            'series_id' => $series_1->id
        ]);
        // $video_1 = Video::factory()->create([
        //     'race_id' => $race_1->id
        // ]);

        // $series_2 = Series::factory()->create();
        // $race_2 = Race::factory()->create([
        //     'series_id' => $series_2->id
        // ]);
        // $video_2 = Video::factory()->create([
        //     'race_id' => $race_2->id
        // ]);

        $data = [
            'series_id' => $series_1->id,
            'user_id' => $user_1->id
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

        // $this->get(route('follow.series.show', ['series' => $series_2]))
        //     ->assertStatus(200)
        //     ->assertJson([
        //         'follow' => false
        //     ]);

        Mail::assertNothingSent();

        SendFollowedSeriesEmails::dispatchSync();

        Mail::assertNothingQueued();
    }

    /** @test */
    public function the_race_start_time_must_be_within_180_days()
    {
        Mail::fake();
        $user_1 = User::factory()->create();
        // $user_2 = User::factory()->create();
        Passport::actingAs($user_1);

        $series_1 = Series::factory()->create();
        $race_1 = Race::factory()->create([
            'series_id' => $series_1->id,
            'starts_at' => Carbon::now()->subDays(182)
        ]);
        $video_1 = Video::factory()->create([
            'race_id' => $race_1->id
        ]);

        // $series_2 = Series::factory()->create();
        // $race_2 = Race::factory()->create([
        //     'series_id' => $series_2->id
        // ]);
        // $video_2 = Video::factory()->create([
        //     'race_id' => $race_2->id
        // ]);

        $data = [
            'series_id' => $series_1->id,
            'user_id' => $user_1->id
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

        // $this->get(route('follow.series.show', ['series' => $series_2]))
        //     ->assertStatus(200)
        //     ->assertJson([
        //         'follow' => false
        //     ]);

        Mail::assertNothingSent();

        SendFollowedSeriesEmails::dispatchSync();

        Mail::assertNothingQueued();

        $race_1->starts_at = Carbon::now()->subDays(5);
        $race_1->save();

        SendFollowedSeriesEmails::dispatchSync();
        Mail::assertQueued(FollowedSeries::class, 1);
    }

    /** @test */
    public function the_race_must_be_created_in_the_last_7_days()
    {
        Mail::fake();
        $user_1 = User::factory()->create();
        // $user_2 = User::factory()->create();
        Passport::actingAs($user_1);

        $series_1 = Series::factory()->create();
        $race_1 = Race::factory()->create([
            'series_id' => $series_1->id,
            'created_at' => Carbon::now()->subDays(9)
        ]);
        $video_1 = Video::factory()->create([
            'race_id' => $race_1->id
        ]);

        // $series_2 = Series::factory()->create();
        // $race_2 = Race::factory()->create([
        //     'series_id' => $series_2->id
        // ]);
        // $video_2 = Video::factory()->create([
        //     'race_id' => $race_2->id
        // ]);

        $data = [
            'series_id' => $series_1->id,
            'user_id' => $user_1->id
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

        // $this->get(route('follow.series.show', ['series' => $series_2]))
        //     ->assertStatus(200)
        //     ->assertJson([
        //         'follow' => false
        //     ]);

        Mail::assertNothingSent();

        SendFollowedSeriesEmails::dispatchSync();

        Mail::assertNothingQueued();

        $race_1->created_at = Carbon::now()->subDays(6);
        $race_1->save();

        SendFollowedSeriesEmails::dispatchSync();
        Mail::assertQueued(FollowedSeries::class, 1);
    }
}
