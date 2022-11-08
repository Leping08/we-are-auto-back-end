<?php

namespace App\Console;

use App\Jobs\FindPotentialRacesForSeries;
use App\Jobs\SendFollowedSeriesEmails;
use App\Models\Series;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Tests\Unit\FindPotentialRaceTest;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // todo bring back when the race subscriptions are fixed
        // $schedule->call(function () {
        //     SendFollowedSeriesEmails::dispatch();
        // })->weeklyOn(4, '10:00'); // Thursday at 10:00 AM

        $schedule->call(function () {
            Series::all()->each(function (Series $series) {
                FindPotentialRacesForSeries::dispatch($series);
            });
        })->dailyAt('6:00'); // Daily at 6:00 AM
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
