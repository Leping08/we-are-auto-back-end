<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Pick;
use App\Models\User;
use Illuminate\Http\Request;

class RaceResultsController extends Controller
{
    public function show(League $league, User $user)
    {
        return Pick::where('league_id', $league->id)
            ->where('user_id', $user->id)
            ->with(['race.track', 'car'])  //Result object is added from the model $appends attribute
            ->get();
    }
}
