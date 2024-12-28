<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrentPickLeagueController;
use App\Http\Controllers\LeaguesController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\RaceProblemController;
use App\Http\Controllers\RaceRatingController;
use App\Http\Controllers\RaceResultsController;
use App\Http\Controllers\RaceSuggestionController;
use App\Http\Controllers\SeriesCarsController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeriesFollowController;
use App\Http\Controllers\SeriesSeasonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoProgressController;
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
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

//Series
Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
Route::get('/series/{series}', [SeriesController::class, 'show'])->name('series.show');

//SeriesSeason
Route::get('/series/{series}/season/{season}', [SeriesSeasonController::class, 'show'])->name('series_season.show');

//Race
Route::get('/races', [RaceController::class, 'index'])->name('races.index');
Route::get('/races/latest/{count}', [RaceController::class, 'latest'])->name('races.latest');
Route::get('/races/{race}', [RaceController::class, 'show'])->name('races.show');
Route::get('/races/random/{count}', [RaceController::class, 'random'])->name('races.random');

Route::post('/race-problem', [RaceProblemController::class, 'store'])->name('race-problem.store');

//Auth Group
Route::middleware(['auth:api'])->group(function () {
    //User
    Route::get('/user/me', [UserController::class, 'show'])->name('user.show');
    Route::post('/user/{user}', [UserController::class, 'update'])->name('user.update');

    //Cars
    Route::get('/series/{series}/cars', [SeriesCarsController::class, 'show']);

    //Leagues
    Route::get('/leagues', [LeaguesController::class, 'index'])->name('leagues.index');
    Route::get('/leagues/{league}', [LeaguesController::class, 'show'])->name('leagues.show');

    //CurrentPicks
    Route::get('/leagues/{league}/current-picks', [CurrentPickLeagueController::class, 'show']);
    Route::post('/current-picks', [CurrentPickLeagueController::class, 'store']);

    //RaceResults
    Route::get('/race-results/league/{league}/user/{user}', [RaceResultsController::class, 'show']);

    //Suggestion
    Route::post('/races/suggestion', [RaceSuggestionController::class, 'store'])->name('race.suggestion.store');

    //Video Progress
    Route::post('/video-progress', [VideoProgressController::class, 'store'])->name('video-progress.store');

    // Follow Series
    Route::get('/follow-series', [SeriesFollowController::class, 'index'])->name('follow.series.index');
    Route::get('/follow-series/{series}', [SeriesFollowController::class, 'show'])->name('follow.series.show');
    Route::post('/follow-series', [SeriesFollowController::class, 'store'])->name('follow.series.store');

    // Rate Race
    Route::post('race/rate', [RaceRatingController::class, 'store'])->name('race.rate');
});
