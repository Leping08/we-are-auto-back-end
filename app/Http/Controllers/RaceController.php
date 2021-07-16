<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index()
    {
        return Race::all();
    }

    public function show(Race $race): Race
    {
        return $race->load(['series', 'track', 'season', 'videos.platform']);
    }
}
