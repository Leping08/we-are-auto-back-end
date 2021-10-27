<?php

namespace App\Http\Controllers;

use App\Models\RaceSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaceSuggestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'race_id' => ['required', 'exists:App\Models\Race,id'],
            'data' => ['required', 'array']
        ]);

        //Build the data object from the request data, this will sanitize the data in the request
        $filteredData = collect($request->get('data'))->map(function ($raceData) {
            return [
                'part' => $raceData['part'] ?? null,
                'link' => $raceData['link'] ?? null
            ];
        });

        return RaceSuggestion::create([
            'race_id' => $request->get('race_id'),
            'user_id' => Auth::id(),
            'data' => $filteredData
        ]);
    }
}
