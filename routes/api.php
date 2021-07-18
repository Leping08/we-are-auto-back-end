<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Passport token routes are in App\Providers\AuthServiceProvider
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['api']], function() {

    //Series
    Route::get('/series', [\App\Http\Controllers\SeriesController::class, 'index'])->name('series.index');
    Route::get('/series/{series}', [\App\Http\Controllers\SeriesController::class, 'show'])->name('series.show');

    //Race
    Route::get('/races', [\App\Http\Controllers\RaceController::class, 'index'])->name('races.index');
    Route::get('/races/latest/{count}', [\App\Http\Controllers\RaceController::class, 'latest'])->name('races.latest');
    Route::get('/races/{race}', [\App\Http\Controllers\RaceController::class, 'show'])->name('races.show');



    //Auth Group
    Route::group(['middleware' => ['auth']], function() {
        //User
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('user.index');

        //Cars
        Route::get('/series/{series}/cars', [\App\Http\Controllers\SeriesCarsController::class, 'show']);

        //Leagues
        Route::get('/leagues', [\App\Http\Controllers\LeaguesController::class, 'index'])->name('leagues.index');
        Route::get('/leagues/{league}', [\App\Http\Controllers\LeaguesController::class, 'show'])->name('leagues.show');

        //CurrentPicks
        Route::get('/leagues/{league}/current-picks', [\App\Http\Controllers\CurrentPickLeagueController::class, 'show']);
        Route::post('/current-picks', [\App\Http\Controllers\CurrentPickLeagueController::class, 'store']);

        //RaceResults
        Route::get('/race-results/league/{league}/user/{user}', [\App\Http\Controllers\RaceResultsController::class, 'show']);
    });
});


