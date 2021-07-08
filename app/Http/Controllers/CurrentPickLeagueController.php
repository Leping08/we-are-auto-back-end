<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CurrentPick;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CurrentPickLeagueController extends Controller
{
    public function show(League $league)
    {
        return CurrentPick::where('user_id', Auth::id())
            ->where('league_id', $league->id)
            ->with(['car.car_class'])
            ->get();
    }

    public function store(Request $request)
    {
        //TODO handel the case where no old car is selected
        $validator = Validator::make($request->all(), [
            'new_car_id' => ['required', 'exists:cars,id'],
            'old_car_id' => ['required', 'exists:cars,id'],
            'league_id' => ['required', 'exists:leagues,id']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user_id = Auth::id();

        $oldPick = CurrentPick::where('car_id', $request->old_car_id)
            ->where('league_id', $request->league_id)
            ->where('user_id', $user_id)
            ->get();

        //They have never picked a car yet so just create the new pick
        if ($oldPick->count() === 0) {
            $pick = CurrentPick::create([
                'car_id' => $request->car_id,
                'league_id' => $request->league_id,
                'user_id' => $user_id
            ]);
            return $pick;
        }

        // If the user already has a pick
        if ($oldPick->count() === 1) {
            $oldCar = Car::find($oldPick->first()->car_id);
            $newCar = Car::find($request->new_car_id);

            //Check if the new car exists at all
            if (!$newCar) {
                return response('The new car does not exists', 500);
            }

            //Check if the old car class is not the same as the new car class
            if (!($oldCar->car_class_id === $newCar->car_class_id)) {
                return response('Pick change is for cars from different classes', 500);
            }
        } else {
            //The the user has more then one pick so abort
            response('User has more then one pick for league car combo', 500);
        }

        //Delete the old pick
        $oldPick->first()->delete();

        //Create the new pick
        $newPick = CurrentPick::create([
             'car_id' => $newCar->id,
             'league_id' => $request->league_id,
             'user_id' => $user_id
        ]);

        return $newPick;
    }
}
