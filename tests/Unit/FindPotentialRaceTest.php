<?php

namespace Tests\Unit;

// use Alaouy\Youtube\Youtube;
use Alaouy\Youtube\Facades\Youtube;
use App\Jobs\FindPotentialRacesForSeries;
use App\Models\Season;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindPotentialRaceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_created_a_potential_race()
    {
        // Create a current season
        Season::factory()->create();

        // Create a series
        $series = Series::factory()->create([
            'settings' => [
                "youtube" => [
                    "channel_id" => "fdsjnfdskjna",
                    "required_key_words" => [
                        "MX-5"
                    ],
                    "min_race_time_in_seconds" => 5000
                ]
            ]
        ]);

        // Fake the data coming back from the youtube api
        Youtube::shouldReceive('listChannelVideos')
            ->andReturn(self::channelVideosResponse());
        Youtube::shouldReceive('getVideoInfo')
            ->andReturn(self::videoInfoResponse());

        FindPotentialRacesForSeries::dispatch($series);

        $this->assertDatabaseCount('potential_races', 1);
    }

    /** @test */
    public function it_does_not_create_a_potential_race_if_the_title_does_not_contain_the_keyword()
    {
        // Create a current season
        Season::factory()->create();

        // Create a series
        $series = Series::factory()->create([
            'settings' => [
                "youtube" => [
                    "channel_id" => "fdsjnfdskjna",
                    "required_key_words" => ['this is not in the title'],
                    "min_race_time_in_seconds" => 5000
                ]
            ]
        ]);

        // Fake the data coming back from the youtube api
        Youtube::shouldReceive('listChannelVideos')
            ->andReturn(self::channelVideosResponse());
        Youtube::shouldReceive('getVideoInfo')
            ->andReturn(self::videoInfoResponse());

        FindPotentialRacesForSeries::dispatch($series);

        $this->assertDatabaseCount('potential_races', 0);
    }

    /** @test */
    public function it_does_not_create_a_potential_race_if_the_duration_is_greater_than_the_min_race_time_in_seconds()
    {
        // Create a current season
        Season::factory()->create();

        // Create a series
        $series = Series::factory()->create([
            'settings' => [
                "youtube" => [
                    "channel_id" => "fdsjnfdskjna",
                    "required_key_words" => [
                        "MX-5"
                    ],
                    "min_race_time_in_seconds" => 500
                ]
            ]
        ]);

        // Fake the data coming back from the youtube api
        Youtube::shouldReceive('listChannelVideos')
            ->andReturn(self::channelVideosResponse());
        Youtube::shouldReceive('getVideoInfo')
            ->andReturn(self::videoInfoResponse());

        FindPotentialRacesForSeries::dispatch($series);

        $this->assertDatabaseCount('potential_races', 0);
    }

    /** @test */
    public function it_does_not_create_a_potential_race_if_youtube_video_id_is_already_in_the_potential_races_table()
    {
        // Create a current season
        Season::factory()->create();

        // Create a series
        $series = Series::factory()->create([
            'settings' => [
                "youtube" => [
                    "channel_id" => "fdsjnfdskjna",
                    "required_key_words" => [
                        "MX-5"
                    ],
                    "min_race_time_in_seconds" => 5000
                ]
            ]
        ]);

        // Fake the data coming back from the youtube api
        Youtube::shouldReceive('listChannelVideos')
            ->andReturn(self::channelVideosResponse());
        Youtube::shouldReceive('getVideoInfo')
            ->andReturn(self::videoInfoResponse());

        FindPotentialRacesForSeries::dispatch($series);

        $this->assertDatabaseCount('potential_races', 1);

        FindPotentialRacesForSeries::dispatch($series);

        $this->assertDatabaseCount('potential_races', 1);
    }

    public static function channelVideosResponse()
    {
        return [
            [
                "kind" => "youtube#searchResult",
                "etag" => "gUSb2IlzT940b0JfsVbTG0fvx_E",
                "id" => [
                    "kind" => "youtube#video",
                    "videoId" => "CqNRfHzoJ3s"
                ],
                "snippet" => [
                    "publishedAt" => "2021-03-19T14:03:11Z",
                    "channelId" => "UC58em84jwiyM20qR-iqBDZw",
                    "title" => "MX-5 Sebring",
                    "description" => "Testing MX-5 race video",
                    "thumbnails" => [
                        "default" => [
                            "url" => "https://i.ytimg.com/vi/CqNRfHzoJ3s/default.jpg",
                            "width" => 120,
                            "height" => 90,
                        ],
                        "medium" => [
                            "url" => "https://i.ytimg.com/vi/CqNRfHzoJ3s/mqdefault.jpg",
                            "width" => 320,
                            "height" => 180,
                        ],
                        "high" => [
                            "url" => "https://i.ytimg.com/vi/CqNRfHzoJ3s/hqdefault.jpg",
                            "width" => 480,
                            "height" => 360,
                        ],
                    ],
                    "channelTitle" => "IMSA Official",
                    "liveBroadcastContent" => "none",
                    "publishTime" => "2021-03-19T14:03:11Z",
                ],
            ],
            [
                "kind" => "youtube#searchResult",
                "etag" => "G4v8xgCq8lTIobYkWfiD41I0amQ",
                "id" => [
                    "kind" => "youtube#video",
                    "videoId" => "dZcyfS3TkKA"
                ],
                "snippet" => [
                    "publishedAt" => "2021-08-24T14:15:55Z",
                    "channelId" => "UC58em84jwiyM20qR-iqBDZw",
                    "title" => "Cadillac to Enter LMDh in 2023",
                    "description" => "Cadillac becomes fifth manufacturer to announce LMDh program for 2023.",
                    "thumbnails" => [
                        "default" => [
                            "url" => "https://i.ytimg.com/vi/dZcyfS3TkKA/default.jpg",
                            "width" => 120,
                            "height" => 90,
                        ],
                        "medium" => [
                            "url" => "https://i.ytimg.com/vi/dZcyfS3TkKA/mqdefault.jpg",
                            "width" => 320,
                            "height" => 180,
                        ],
                        "high" => [
                            "url" => "https://i.ytimg.com/vi/dZcyfS3TkKA/hqdefault.jpg",
                            "width" => 480,
                            "height" => 360,
                        ],
                    ],
                    "channelTitle" => "IMSA Official",
                    "liveBroadcastContent" => "none",
                    "publishTime" => "2021-08-24T14:15:55Z",
                ],
            ],
        ];
    }

    public static function videoInfoResponse()
    {
        return [
            [
                'kind' => 'youtube#video',
                'etag' => 'S02DE8xIYJ3TL0q2Zk_PQM_JrY0',
                'id' => 'dZcyfS3TkKA',
                'snippet' => [
                    'publishedAt' => '2021-08-24T14:15:55Z',
                    'channelId' => 'UC58em84jwiyM20qR-iqBDZw',
                    'title' => 'MX-5 Sebring',
                    'description' => 'Testing MX-5 race video',
                    'thumbnails' => [
                        'default' => [
                            'url' => 'https://i.ytimg.com/vi/dZcyfS3TkKA/default.jpg',
                            'width' => 120,
                            'height' => 90,
                        ],
                        'medium' => [
                            'url' => 'https://i.ytimg.com/vi/dZcyfS3TkKA/mqdefault.jpg',
                            'width' => 320,
                            'height' => 180,
                        ],
                        'high' => [
                            'url' => 'https://i.ytimg.com/vi/dZcyfS3TkKA/hqdefault.jpg',
                            'width' => 480,
                            'height' => 360,
                        ],
                        'standard' => [
                            'url' => 'https://i.ytimg.com/vi/dZcyfS3TkKA/sddefault.jpg',
                            'width' => 640,
                            'height' => 480,
                        ],
                    ],
                    'channelTitle' => 'IMSA Official',
                    'tags' => [
                        0 => 'IMSA',
                        1 => 'International Motor Sports Association',
                        2 => 'Cadillac',
                    ],
                    'categoryId' => '17',
                    'liveBroadcastContent' => 'none',
                    'localized' => [
                        'title' => 'Cadillac to Enter LMDh in 2023',
                        'description' => 'Cadillac becomes fifth manufacturer to announce LMDh program for 2023.',
                    ],
                ],
                'contentDetails' => [
                    'duration' => 'PT3000S', // 3000 seconds
                    'dimension' => '2d',
                    'definition' => 'hd',
                    'caption' => 'false',
                    'licensedContent' => false,
                    'contentRating' => [],
                    'projection' => 'rectangular',
                ],
                'status' => [
                    'uploadStatus' => 'processed',
                    'privacyStatus' => 'public',
                    'license' => 'youtube',
                    'embeddable' => true,
                    'publicStatsViewable' => true,
                    'madeForKids' => false,
                ],
                'statistics' => [
                    'viewCount' => '14814',
                    'likeCount' => '617',
                    'favoriteCount' => '0',
                    'commentCount' => '108',
                ],
                'player' => [
                    'embedHtml' => '<iframe width="480" height="270" src="//www.youtube.com/embed/dZcyfS3TkKA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
                ],
            ]
        ];
    }
}
