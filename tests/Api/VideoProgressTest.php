<?php

namespace Tests\Api;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class VideoProgressTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_save_progress_for_a_video()
    {
        $this->assertCount(0, VideoProgress::all());

        $user = User::factory()->create();
        Passport::actingAs($user);

        $video = Video::factory()->create([
            'start_time' => 10,
            'end_time' => 200,
        ]);

        $response = $this->json('POST', route('video-progress.store'), [
            'user_id' => $user->id,
            'video_id' => $video->id,
            'seconds' => $this->faker->numberBetween(20,100)
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_deletes_any_past_progress_on_that_video()
    {
        $this->assertCount(0, VideoProgress::all());

        $user = User::factory()->create();
        Passport::actingAs($user);

        $video = Video::factory()->create([
            'start_time' => 10,
            'end_time' => 200,
        ]);

        VideoProgress::factory()->create([
            'video_id' => $video->id,
            'user_id' => $user->id,
            'seconds' => 15
        ]);

        $this->assertCount(1, VideoProgress::all());

        $response = $this->json('POST', route('video-progress.store'), [
            'video_id' => $video->id,
            'seconds' => $this->faker->numberBetween(20,100)
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, VideoProgress::all());
    }

    /** @test */
    public function a_user_must_be_logged_in_to_store_video_progress()
    {
        $this->assertCount(0, VideoProgress::all());

        $video = Video::factory()->create([
            'start_time' => 10,
            'end_time' => 200,
        ]);

        $response = $this->json('POST', route('video-progress.store'), [
            'video_id' => $video->id,
            'seconds' => $this->faker->numberBetween(20,100)
        ]);

        $response->assertStatus(401);
    }
}
