<?php

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
    return redirect('/field');
});

Route::middleware(['web'])->group(function () {
  Route::prefix('field')->group(function () {
    /* Get */
    Route::get('/', 'FieldController@home');
    Route::get('schedule', 'FieldController@schedule');
    Route::get('report', 'FieldController@report');
    Route::get('promotions', 'FieldController@promotions');
    Route::get('promotions/add', 'FieldController@add_promotion_list');
    Route::get('promotions/edit/{id}', 'FieldController@edit_promotion_list');
    /* Post */
    Route::post('promotions/add', 'FieldController@add_promotion');
    Route::post('promotions/edit/{id}', 'FieldController@edit_promotion');
    Route::post('schedule', 'FieldController@handle_schedule');
  });
});
