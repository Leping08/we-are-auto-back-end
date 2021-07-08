<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function show(Race $race)
    {
        //TODO Authorize someone can look at the race and picks
        return $race->load(['series', 'track', 'season']);
    }
}
