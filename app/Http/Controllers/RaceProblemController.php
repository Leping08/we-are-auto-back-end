<?php

namespace App\Http\Controllers;

use App\Models\RaceProblem;
use Illuminate\Http\Request;

class RaceProblemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'race_id' => ['required', 'integer', 'exists:races,id'],
            'description' => ['required', 'min:2', 'max:5000']
        ]);

        return RaceProblem::create([
            'user_id' => $request->get('user_id'),
            'race_id' => $request->get('race_id'),
            'description' => $request->get('description')
        ]);

        // todo send out admin email
    }
}
