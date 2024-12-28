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

        $rating = RaceRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'race_id' => $validated['race_id']
            ],
            ['rating' => $validated['rating']]
        );

        $race = Race::find($validated['race_id']);
        $race->clearRatingCache();

        return response()->json([
            'rating' => $rating,
            'average' => $race->averageRating()
        ], 201);
    }
}
