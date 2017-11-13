<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;

class CustomerApiController extends Controller
{
    public function __construct()
    {
        $this->controller = new CustomerController();
    }
    /* --Account */
    public function account_login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        return json_encode($this->format($this->controller->account_login($username, $password)));
    }

    public function account_register(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');
        $username = $request->input('username');
        $password = $request->input('password');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        return json_encode($this->format($this->controller->account_register($name, $email, $phone_number, $username, $password, $latitude, $longitude)));
    }

    public function account_edit(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');
        $username = $request->input('username');
        $password = $request->input('password');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        return json_encode($this->format($this->controller->account_edit($id, $name, $email, $phone_number, $username, $password, $latitude, $longitude)));
    }

    public function account_change_password(Request $request)
    {
        $id = $request->input('id');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');
        $re_password = $request->input('re_password');
        return json_encode($this->format($this->controller->account_change_password($id, $old_password, $new_password)));
    }

    public function account_forget(Request $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        return json_encode($this->format($this->controller->account_forget($username, $email)));
    }

    /* Friend */
    public function friend_add(Request $request)
    {
        $friend_id = $request->input('friend_id');
        $customer_id = $request->input('customer_id');
        return json_encode($this->format($this->controller->friend_add($friend_id, $customer_id)));
    }

    public function friend_accept(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->friend_accept($id)));
    }

    public function friend_reject(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->friend_reject($id)));
    }

    public function friend_delete(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->friend_delete($id)));
    }

    /* Mobile */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        return json_encode($this->format($this->controller->search($keyword)));
    }
    public function search_new_friend(Request $request)
    {
        $keyword = $request->input('keyword');
        $id = $request->input('id');
        return json_encode($this->format($this->controller->search_new_friend($keyword, $id)));
    }
    public function meetings(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->meetings($id)));
    }
    public function teams(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->teams($id)));
    }
    public function friends(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->friends($id)));
    }
    public function requests(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->requests($id)));
    }
    public function reserves(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->format($this->controller->reserves($id)));
    }
}
