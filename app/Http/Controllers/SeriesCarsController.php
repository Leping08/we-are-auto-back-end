<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesCarsController extends Controller
{
    public function show(Series $series)
    {
        //TODO remove unused car_classes that's being sent in the response
        $series['unique_car_classes'] = $series->unique_car_classes();
        return $series->load('cars');
    }
}
