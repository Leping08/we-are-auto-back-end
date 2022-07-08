<?php

namespace App\Jobs;

use App\Mail\FollowedSeries;
use App\Models\Race;
use App\Models\Series;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFollowedSeriesEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get all the users that are following a series and load the races
        // that were added within the last week and the race started within
        // the last 180 days
        $users = User::with(['series_following.races' => function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(7))
                      ->where('starts_at', '>=', Carbon::now()->subDays(180));
            }])
            ->get()
            ->each(function ($user) {
                return collect(data_get($user, 'series_following'))->each(function ($series) {
                    return collect(data_get($series, 'races'))->each(function ($race) {
                        return $race->load('videos');
                    });
                });
            })
            ->each(function ($user) { // the race has videos
                return collect(data_get($user, 'series_following'))->each(function ($series) {
                    $series['races'] = collect(data_get($series, 'races'))->filter(function ($race) {
                        return $race->videos->count();
                    });
                });
            })
            ->each(function ($user) { // the series has races
                $user['series_following'] = collect(data_get($user, 'series_following'))->filter(function ($series) {
                    return $series->races->count();
                });
            })
            ->filter(function ($user) { // the user is following a series with new races and videos
                return collect(data_get($user, 'series_following'))->count();
            });

        // Send email to each user using the queue
        $users->each(function ($user) {
            Mail::to($user->email)->queue(new FollowedSeries($user));
        });
    }
}
