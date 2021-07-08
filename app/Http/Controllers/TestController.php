<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarClass;
use App\Models\Race;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

class TestController extends Controller
{
    public function index()
    {
        $pathToFile = Storage::path('sebring.csv');
        $race_id = 2;

        //Create class if it does not exists
        $classes = SimpleExcelReader::create($pathToFile, 'csv')->useDelimiter(';')->getRows();
        $classes->unique('CLASS')->each(function ($row) {
            CarClass::firstOrCreate([
                'name' => $row['CLASS']
            ]);
        });

        //Create cars
        $cars = SimpleExcelReader::create($pathToFile, 'csv')->useDelimiter(';')->getRows();
        $cars->each(function ($row) {
            Car::firstOrCreate([
                'series_id' => 1,
                'number' => $row['NUMBER'],
                'car_class_id' => CarClass::where('name', '=', $row['CLASS'])->first()->id ?? 0
            ]);
        });

        //Create results
        //Get all the classes
        $classes = SimpleExcelReader::create($pathToFile, 'csv')->useDelimiter(';')->getRows();
        // Loop over the unique classes
        $classes->unique('CLASS')->each(function ($row) use ($race_id, $pathToFile) {
            $results = SimpleExcelReader::create($pathToFile, 'csv')->useDelimiter(';')->getRows();
            $index = 0;
            //Get all the cars for just that class and loop over them
            $results->where('CLASS', '=', $row['CLASS'])->each(function ($row) use ($race_id, &$index) {
                Result::firstOrCreate([
                    'end_position' => (int)($index + 1),
                    'race_id' => $race_id,
                    'car_id' => Car::where('number', '=', $row['NUMBER'])->first()->id ?? 0,
                ]);
                $index++;
            });
        });

        return 'done';
    }

    public function count(Race $race, User $user)
    {
        return $race->user_picks($user);
    }

    public function cars()
    {
        return Car::with(['car_class', 'series'])->get();
    }
}
