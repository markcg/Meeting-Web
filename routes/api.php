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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//   return $request->user();
// });

Route::middleware(['api'])->group(function () {
  Route::prefix('field')->group(function () {
    /* Get */
    Route::get('schedule/add', 'FieldController@add_schedule');
    Route::get('schedule/delete/{id}', 'FieldController@delete_schedule');
    Route::get('schedule/confirm/{id}', 'FieldController@confirm_schedule');
    Route::get('schedule/reserve/{id}', 'FieldController@reserve_schedule');
  });
});
