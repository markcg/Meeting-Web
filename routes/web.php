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
    return view('welcome');
});

Route::middleware(['web'])->group(function () {
  Route::prefix('field')->group(function () {
    /* Get */
    Route::get('/', 'HomeController@home');
    Route::get('schedule', 'HomeController@schedule');
    Route::get('report', 'HomeController@report');
    Route::get('promotions', 'HomeController@promotions');
    Route::get('promotions/add', 'HomeController@add_promotion_list');
    Route::get('promotions/edit/{id}', 'HomeController@update_promotion_list');
    /* Post */
    Route::post('promotions/add', 'HomeController@add_promotion');
  });
});
