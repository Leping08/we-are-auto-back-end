<?php

namespace App\Http\Controllers;

use App\Mail\NewRaceProblem;
use App\Models\RaceProblem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RaceProblemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'race_id' => ['required', 'integer', 'exists:races,id'],
            'description' => ['required', 'min:2', 'max:5000']
        ]);

        $newRace = RaceProblem::create([
            'user_id' => $request->get('user_id'),
            'race_id' => $request->get('race_id'),
            'description' => $request->get('description')
        ]);

        $raceReportEmail = RaceProblem::where('id', $newRace->id)->with(['user', 'race'])->first();
        collect(config('mail.addresses.admin'))->each(function ($email) use ($raceReportEmail) {
            Mail::to($email)->queue(new NewRaceProblem($raceReportEmail));
        });

        return $newRace;
    }
}
