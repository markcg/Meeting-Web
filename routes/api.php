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

Route::middleware(['api'])->group(
    function () {
        Route::prefix('field')->group(
            function () {
                /* Get */
                Route::get('login', 'FieldApiController@login');
                Route::get('register', 'FieldApiController@register');
                Route::get('edit', 'FieldApiController@esit');
                Route::get('search', 'FieldApiController@search');
                Route::get('schedule-by-name', 'FieldApiController@scheduleByName');
                Route::get('schedule-by-id', 'FieldApiController@scheduleById');
                Route::get('forget-password', 'FieldApiController@forget_password');
                Route::get('change-password', 'FieldApiController@change_password');
                /* Get */
                Route::get('schedule/add', 'FieldApiController@add_schedule');
                Route::get('schedule/delete/{id}', 'FieldApiController@delete_schedule');
                Route::get('schedule/confirm/{id}', 'FieldApiController@confirm_schedule');
                Route::get('schedule/reserve', 'FieldApiController@reserve_schedule');
                Route::get('schedule/reserve-by-user', 'FieldApiController@reserve_schedule_by_user');
                Route::get('schedule/reserve-by-meeting', 'FieldApiController@reserve_schedule_by_meeting');
            }
        );

        Route::prefix('customer')->group(
            function () {
                /* Get */
                Route::get('login', 'CustomerApiController@account_login');
                Route::get('register', 'CustomerApiController@account_register');
                Route::get('edit', 'CustomerApiController@account_edit');
                Route::get('search', 'CustomerApiController@search');
                Route::get('forget-password', 'CustomerApiController@account_forget');
                Route::get('change-password', 'CustomerApiController@account_change_password');

                Route::get('friend/add', 'CustomerApiController@friend_add');
                Route::get('friend/accept', 'CustomerApiController@friend_accept');
                Route::get('friend/reject', 'CustomerApiController@friend_reject');
                Route::get('friend/delete', 'CustomerApiController@friend_delete');
                Route::get('friend/new/search', 'CustomerApiController@search_new_friend');

                Route::get('get/meetings', 'CustomerApiController@meetings');
                Route::get('get/teams', 'CustomerApiController@teams');
                Route::get('get/friends', 'CustomerApiController@friends');
                Route::get('get/requests', 'CustomerApiController@requests');
                Route::get('get/reserves', 'CustomerApiController@reserves');
            }
        );

        Route::prefix('meeting')->group(
            function () {
                /* Get */
                Route::get('create', 'MeetingApiController@create');
                Route::get('delete', 'MeetingApiController@delete');
                Route::get('team/add', 'MeetingApiController@add_team');
                Route::get('team/delete', 'MeetingApiController@delete_team');
                Route::get('team/new/search', 'MeetingApiController@search_new_team');
                Route::get('search', 'MeetingApiController@search');
                Route::get('get', 'MeetingApiController@get');
                Route::get('get/teams', 'MeetingApiController@teams');
                Route::get('get/schedules', 'MeetingApiController@schedules');
                Route::get('get/optimize', 'MeetingApiController@optimize');
            }
        );

        Route::prefix('team')->group(
            function () {
                /* Get */
                Route::get('create', 'TeamApiController@create');
                Route::get('delete', 'TeamApiController@delete');
                Route::get('member/add', 'TeamApiController@add_member');
                Route::get('member/delete', 'TeamApiController@delete_member');
                Route::get('member/new/search', 'TeamApiController@search_new_member');
                Route::get('search', 'TeamApiController@search');
                Route::get('get', 'TeamApiController@get');
                Route::get('get/members', 'TeamApiController@members');
                Route::get('get/meetings', 'TeamApiController@meetings');
            }
        );
    }
);
