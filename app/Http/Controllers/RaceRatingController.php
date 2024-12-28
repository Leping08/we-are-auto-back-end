<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\RaceRating;
use Illuminate\Http\Request;

class RaceRatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'race_id' => 'required|exists:races,id',
            'rating' => 'required|integer|between:1,5'
        ]);

        $rating = RaceRating::create([
            'user_id' => auth()->id(),
            'race_id' => $validated['race_id'],
            'rating' => $validated['rating']
        ]);

        $race = Race::find($validated['race_id']);
        $race->clearRatingCache();

        return response()->json([
            'rating' => $rating,
            'average' => $race->averageRating()
        ], 201);
    }

    public function update(Request $request, Race $race)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5'
        ]);

        $rating = $race->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $validated['rating']]
        );

        $race->clearRatingCache();

        return response()->json([
            'rating' => $rating,
            'average' => $race->averageRating()
        ]);
    }

    public function destroy(Race $race)
    {
        $race->ratings()->where('user_id', auth()->id())->delete();
        $race->clearRatingCache();
        return response()->noContent();
    }
}
