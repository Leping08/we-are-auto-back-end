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

//Series
Route::get('/series', [\App\Http\Controllers\SeriesController::class, 'index'])->name('series.index');
Route::get('/series/{series}', [\App\Http\Controllers\SeriesController::class, 'show'])->name('series.show');

//SeriesSeason
Route::get('/series/{series}/season/{season}', [\App\Http\Controllers\SeriesSeasonController::class, 'show'])->name('series_season.show');

//Race
Route::get('/races', [\App\Http\Controllers\RaceController::class, 'index'])->name('races.index');
Route::get('/races/latest/{count}', [\App\Http\Controllers\RaceController::class, 'latest'])->name('races.latest');
Route::get('/races/{race}', [\App\Http\Controllers\RaceController::class, 'show'])->name('races.show');

Route::post('/race-problem', [\App\Http\Controllers\RaceProblemController::class, 'store'])->name('race-problem.store');

//Auth Group
Route::middleware(['auth:api'])->group(function () {
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

    //Suggestion
    Route::post('/races/suggestion', [\App\Http\Controllers\RaceSuggestionController::class, 'store'])->name('race.suggestion.store');

    //Video Progress
    Route::post('/video-progress', [\App\Http\Controllers\VideoProgressController::class, 'store'])->name('video-progress.store');

    // Follow Series
    Route::get('/follow-series/{series}', [\App\Http\Controllers\SeriesFollowController::class, 'show'])->name('follow.series.show');
    Route::post('/follow-series', [\App\Http\Controllers\SeriesFollowController::class, 'store'])->name('follow.series.store');
});
