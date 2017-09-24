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
        return json_encode($this->controller->account_login($request));
    }

    public function account_register(Request $request)
    {
        return json_encode($this->controller->account_register($request));
    }

    public function account_edit(Request $request)
    {
        return json_encode($this->controller->account_edit($request));
    }

    public function account_change_password(Request $request)
    {
        return json_encode($this->controller->account_change_password($request));
    }

    public function account_forget(Request $request)
    {
        return json_encode($this->controller->account_forget($request));
    }

    /* Friend */
    public function friend_add(Request $request)
    {
        return json_encode($this->controller->friend_add($request));
    }

    public function friend_accept(Request $request)
    {
        return json_encode($this->controller->friend_accept($request));
    }

    public function friend_reject(Request $request)
    {
        return json_encode($this->controller->friend_reject($request));
    }

    public function friend_delete(Request $request)
    {
        return json_encode($this->controller->friend_delete($request));
    }

    /* Mobile */
    public function search(Request $request)
    {
        return json_encode($this->controller->search($request));
    }
    public function meetings(Request $request)
    {
        return json_encode($this->controller->meetings($request));
    }
    public function teams(Request $request)
    {
        return json_encode($this->controller->teams($request));
    }
    public function friends(Request $request)
    {
        return json_encode($this->controller->friends($request));
    }
    public function requests(Request $request)
    {
        return json_encode($this->controller->requests($request));
    }
    public function reserves(Request $request)
    {
        return json_encode($this->controller->reserves($request));
    }
}
