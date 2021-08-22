<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
