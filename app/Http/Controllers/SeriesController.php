<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index()
    {
        $series = Series::withCount('races')
                        ->with('tags')
                        ->orderBy('full_name')
                        ->get();
        $series->map(function ($series) {
            return $series['seasons'] = $series->seasons();
        });
        return $series->all();
    }

    public function show(Series $series)
    {
        return $series::with(['cars.car_class', 'seasons'])->get();
    }
}
