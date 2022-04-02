<?php

namespace App\Http\Controllers;

use App\Models\FollowSeries;
use App\Models\Series;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesFollowController extends Controller
{
    public function show(Series $series)
    {
        $user = Auth::user();

        $follow = FollowSeries::where('user_id', $user->id)
            ->where('series_id', $series->id)
            ->first();

        return response()->json([
            'follow' => $follow ? true : false
        ]);
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();

        $request->validate([
            'user_id' => ['required', 'integer'],
            'series_id' => ['required', 'integer'],
        ]);

        $user = User::findOrFail($request->get('user_id'));
        $series = Series::findOrFail($request->get('series_id'));

        if ($authUser->id !== $user->id) {
            return response()->json([
                'message' => 'You can only follow your own series.'
            ], 403);
        }

        $followSeries = FollowSeries::where('user_id', $user->id)
            ->where('series_id', $series->id)
            ->get();

        if ($followSeries->count() > 0) {
            return response()->json([
                'message' => 'You are already following this series.'
            ], 400);
        }

        $authUser->series_following()->attach($series);

        return response()->json([
            'message' => 'Series followed successfully',
        ], 302);
    }
}
