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
        return json_encode($this->controller->account_login($request));
    }

    public function register(Request $request)
    {
        return json_encode($this->controller->account_register($request));
    }

    public function edit(Request $request)
    {
        return json_encode($this->controller->account_edit($request));
    }

    public function change_password(Request $request)
    {
        return json_encode($this->controller->account_change_password($request));
    }

    public function forget_password(Request $request)
    {
        return json_encode($this->controller->account_forget($request));
    }

    public function search(Request $request)
    {
        return json_encode($this->controller->search($request));
    }

    /* Schedule */
    public function add_schedule(Request $request)
    {
        return json_encode($this->controller->add_schedule($request));
    }
    public function delete_schedule(Request $request)
    {
        return json_encode($this->controller->delete_schedule($request->input('id')));
    }
    public function reserve_schedule(Request $request)
    {
        return json_encode($this->controller->reserve_schedule($request));
    }
    public function confirm_schedule(Request $request)
    {
        return json_encode($this->controller->confirm_schedule($request));
    }
}
