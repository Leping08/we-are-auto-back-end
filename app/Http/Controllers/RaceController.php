<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index()
    {
        return Race::with(['track', 'series', 'season', 'videos.platform'])
                    ->get();
    }

    public function show(Race $race): Race
    {
        return $race->load(['series', 'track', 'season', 'videos.platform']);
    }

    public function latest(int $count)
    {
        return Race::with(['track', 'series', 'season', 'videos.platform'])
                    ->latest()
                    ->take($count)
                    ->get();
    }
}
