<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaguesController extends Controller
{
    public function index()
    {
        /* @var $user \App\Models\User */
        $user = Auth::user();
        //TODO add user position in league
        return $user->leagues->load('series.active_season_races')->loadCount(['users']);
    }

    public function show(League $league)
    {
        $this->authorize('view', $league);
        return $league->loadCount(['users'])
            ->load(['users', 'series']);
    }
}
