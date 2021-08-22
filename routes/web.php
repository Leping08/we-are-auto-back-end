<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);
Route::get('/races/{race}/users/{user}', [\App\Http\Controllers\TestController::class, 'count']);

Route::get('/test', function () {
    // \App\Library\RaceData\Imsa::test();
    return 'done';
});
