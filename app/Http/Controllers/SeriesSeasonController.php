<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\Season;
use App\Models\Series;

class SeriesSeasonController extends Controller
{
    public function show(Series $series, Season $season)
    {
        return Race::with(['track', 'videos.platform', 'videos.progress'])
            ->where('series_id', $series->id)
            ->where('season_id', $season->id)
            ->orderBy('starts_at', 'asc')
            ->get();
    }
}
