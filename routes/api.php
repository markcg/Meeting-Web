<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware(['api'])->group(function () {
  Route::prefix('field')->group(function () {
    /* Get */
    Route::get('schedule/add', 'HomeController@add_schedule');
    Route::get('schedule/delete/{id}', 'HomeController@delete_schedule');
    Route::get('schedule/confirm/{id}', 'HomeController@confirm_schedule');
    Route::get('schedule/reserve/{id}', 'HomeController@reserve_schedule');
  });
});
