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
            'data' => ['required', 'array'] //TODO validate the structure of the data
        ]);

        return RaceSuggestion::create([
            'race_id' => $request->get('race_id'),
            'user_id' => Auth::id(),
            'data' => $request->get('data')
        ]);
    }
}
