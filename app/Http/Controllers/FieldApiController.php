<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FieldController;
use App\Models\Customer;

class FieldApiController extends Controller
{
    public function __construct()
    {
        $this->controller = new FieldController();
    }
    /* --Account */
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        return json_encode($this->format($this->controller->account_login($username, $password)));
    }

    public function register(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $email = $request->input('email');
        $address = $request->input('address');
        $phone_number = $request->input('phone_number');
        $username = $request->input('username');
        $password = $request->input('password');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        return json_encode($this->format($this->controller->account_register($name, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude)));
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $email = $request->input('email');
        $address = $request->input('address');
        $phone_number = $request->input('phone_number');
        $username = $request->input('username');
        $password = $request->input('password');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        return json_encode($this->format($this->controller->account_edit($id, $name, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude)));
    }

    public function change_password(Request $request)
    {
        $id = $request->input('id');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');
        $re_password = $request->input('re_password');
        return json_encode($this->format($this->controller->account_change_password($id, $old_password, $new_password)));
    }

    public function forget_password(Request $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        return json_encode($this->format($this->controller->account_forget($username, $email)));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        return json_encode($this->format($this->controller->search($keyword)));
    }

    public function scheduleByName(Request $request)
    {
        $keyword = $request->input('keyword');
        $date = date('Y-m-d', strtotime($request->input('date')));
        return json_encode($this->format($this->controller->scheduleByName($keyword, $date)));
    }

    public function scheduleById(Request $request)
    {
        $id = $request->input('id');
        $date = date('Y-m-d', strtotime($request->input('date')));
        return json_encode($this->format($this->controller->scheduleById($id, $date)));
    }
    /* Schedule */
    public function add_schedule(Request $request)
    {
        return json_encode($this->format($this->controller->add_schedule($request)));
    }
    public function delete_schedule(Request $request)
    {
        return json_encode($this->format($this->controller->delete_schedule($request->input('id'))));
    }
    public function reserve_schedule(Request $request)
    {
        return json_encode($this->format($this->controller->schedule_reserve($request)));
    }
    public function reserve_schedule_by_user(Request $request)
    {
        return json_encode($this->format($this->controller->schedule_reserve_by_user($request)));
    }
    public function reserve_schedule_by_meeting(Request $request)
    {
        return json_encode($this->format($this->controller->schedule_reserve_by_meeting($request)));
    }
    public function confirm_schedule(Request $request)
    {
        return json_encode($this->format($this->controller->confirm_schedule($request)));
    }
}
