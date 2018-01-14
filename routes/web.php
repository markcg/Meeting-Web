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

Route::get(
    '/', function () {
        return redirect('field/login');
    }
);

Route::middleware(['web'])->group(
    function () {
        Route::prefix('field')->group(
            function () {
                /* Get */
                Route::get('/', 'FieldController@home');
                Route::get('login', 'FieldController@login');
                Route::get('register', 'FieldController@register');
                Route::get('forgot-password', 'FieldController@forgot_password');
                Route::get('logout', 'FieldController@logout');
                Route::get('edit', 'FieldController@edit');
                Route::get('change-password', 'FieldController@change_password');
                Route::get('schedule', 'FieldController@schedule');
                Route::get('report', 'FieldController@report');
                Route::get('promotions', 'FieldController@promotions');
                Route::get('promotions/add', 'FieldController@add_promotion_list');
                Route::get('promotions/edit/{id}', 'FieldController@edit_promotion_list');
                Route::get('schedule/confirm', 'FieldController@handle_report_schedule_confirm');
                Route::get('schedule/cancel', 'FieldController@handle_report_schedule_cancel');
                Route::get('schedule/show', 'FieldController@handle_report_schedule_show');
                Route::get('schedule/not-show', 'FieldController@handle_report_schedule_not_show');

                /* Post */
                Route::post('login', 'FieldController@handle_login');
                Route::post('edit', 'FieldController@handle_edit');
                Route::post('register', 'FieldController@handle_register');
                Route::post('forgot-password', 'FieldController@handle_forgot_password');
                Route::post('change-password', 'FieldController@handle_change_password');
                Route::post('promotions/add', 'FieldController@add_promotion');
                Route::post('promotions/edit/{id}', 'FieldController@edit_promotion');
                Route::post('schedule', 'FieldController@handle_schedule');
            }
        );

        Route::prefix('admin')->group(
            function () {
                /* Get */
                Route::get('/', 'AdminController@home');
                Route::get('login', 'AdminController@login');
                Route::get('logout', 'AdminController@logout');
                Route::get('customers', 'AdminController@customers');
                Route::get('customer/delete/{id}', 'AdminController@delete_customer');
                Route::get('customer/{id}', 'AdminController@customer');
                Route::get('fields', 'AdminController@fields');
                Route::get('field/{id}', 'AdminController@field');
                Route::get('field/delete/{id}', 'AdminController@delete_field');
                Route::get('field/confirm/{id}', 'AdminController@confirm_field');
                Route::get('edit', 'AdminController@edit');
                Route::get('change-password', 'AdminController@change_password');

                /* Post */
                Route::post('login', 'AdminController@handle_login');
                Route::post('field/{id}', 'AdminController@handle_edit_field');
                Route::post('customer/{id}', 'AdminController@handle_edit_customer');
                Route::post('edit', 'AdminController@handle_edit');
                Route::post('change-password', 'AdminController@handle_change_password');

            }
        );
    }
);
