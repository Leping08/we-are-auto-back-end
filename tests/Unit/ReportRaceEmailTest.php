<?php

namespace Tests\Unit;

use App\Mail\NewRaceProblem;
use App\Models\Race;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReportRaceEmailTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function is_sends_an_email_to_each_admin_when_a_race_report_is_submitted()
    {
        Mail::fake();

        $data = [
            'race_id' => Race::factory()->create()->id,
            'description' => $this->faker->sentence()
        ];

        Mail::assertNothingSent();

        $this->post(route('race-problem.store'), $data)
            ->assertStatus(201);

        Mail::assertQueued(NewRaceProblem::class, 1);
    }
}
