<?php

namespace App\Http\Controllers;

use App\Models\Race;

class RaceController extends Controller
{
    public function index()
    {
        return Race::with(['track', 'series', 'season', 'videos.platform', 'videos.progress'])
                    ->get();
    }

    public function show(Race $race)
    {
        return $race->load(['series', 'track', 'season', 'videos.platform', 'videos.progress']);
    }

    public function latest(int $count)
    {
        return Race::with(['track', 'series', 'season', 'videos.platform', 'videos.progress'])
                    ->has('videos')
                    ->latest()
                    ->take($count)
                    ->get();
    }

    public function random(int $count)
    {
        return Race::get()->random($count);
    }
}
