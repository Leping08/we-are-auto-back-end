<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index()
    {
        return Series::all();
    }

    public function show(Series $series)
    {
        return $series::with(['cars.car_class'])->get();
    }
}
