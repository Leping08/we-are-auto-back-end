<?php

namespace App\Jobs;

use Alaouy\Youtube\Facades\Youtube;
use App\Models\PotentialRace;
use App\Models\Season;
use App\Models\Series;
use Carbon\CarbonInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FindPotentialRacesForSeries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Series
     */
    private $series;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Series $series)
    {
        $this->series = $series;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $seriesChannelId = data_get($this->series, 'settings.youtube.channel_id');

        if (!$seriesChannelId) {
            Log::info("{$this->series->name} has no channel id in the settings.");
            return;
        }

        try {
            // This is 100 credits per request on the youtube API
            // For fake data look at \Tests\Unit\FindPotentialRaceTest::channelVideosResponse();
            $seriesVideos = collect(Youtube::listChannelVideos($seriesChannelId, 20));
        } catch (\Exception $e) {
            Log::info("Error getting youtube videos for series: {$this->series->name}");
            Log::error($e->getMessage());
            return;
        }


        try {
            // This is .001 credits per video on the youtube API
            // For fake data look at \Tests\Unit\FindPotentialRaceTest::videoInfoResponse();
            $videosDetails = collect(Youtube::getVideoInfo($seriesVideos->pluck('id.videoId')->toArray()));
        } catch (\Exception $e) {
            Log::info("Error getting videos details for series: {$this->series->name}");
            Log::error($e->getMessage());
            return;
        }

        $videosDetails->each(function ($videoDetails) {
            $seconds = CarbonInterval::fromString(data_get($videoDetails, 'contentDetails.duration'))->totalSeconds;
            $minRaceTimeInSeconds = data_get($this->series, 'settings.youtube.min_race_time_in_seconds');
            $keywords = data_get($this->series, 'settings.youtube.required_key_words');
            $videoTitle = data_get($videoDetails, 'snippet.title');
            $youtubeVideoId = data_get($videoDetails, 'id');

            // Check if the video is over the min race time in seconds
            if (isset($seconds) && isset($minRaceTimeInSeconds) && !($seconds > $minRaceTimeInSeconds)) {
                Log::info("Skipping due to video length of: $seconds seconds and series min race time of $minRaceTimeInSeconds seconds.");
                return true;
            }

            // Check if the title of the video contains all the required keywords
            if (isset($videoTitle) && isset($keywords) && !Str::containsAll($videoTitle, $keywords, true)) {
                Log::info("Skipping due to keywords not found in video title: $videoTitle");
                return true;
            }

            // Check if the video is already in the database
            if (isset($youtubeVideoId) && PotentialRace::where('youtube_video_id', $youtubeVideoId)->withTrashed()->exists()) {
                Log::info("Skipping due to video already in DB: $youtubeVideoId");
                return true;
            }

            PotentialRace::create([
                'track_id' => null, // GuessTrack::guessTrack($videoTitle), // todo make this work
                'title' => $videoTitle,
                'youtube_video_id' => $youtubeVideoId,
                'series_id' => $this->series->id,
                'season_id' => Season::activeSeason()->first()->id,
            ]);
        });
    }

    // /**
    //  *  Send new races email report to admin
    //  *  Mark the potential races as sent
    //  */
    // public static function sendReport()
    // {
    //     $races = PotentialRace::where('email_sent', false)->get();

    //     Mail::to(config('mail.admin_email'))
    //         ->send(new NewRacesReport($races));
    //     Log::info("Sent New Races Report email");

    //     foreach ($races as $race) {
    //         $race->email_sent = true;
    //         $race->save();
    //     }
    // }
}
