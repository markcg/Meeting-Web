<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MeetingController;

class MeetingApiController extends Controller
{
    public function __construct()
    {
        $this->controller = new MeetingController();
    }
    public function create(Request $request)
    {
        return json_encode($this->controller->create($request));
    }

    public function delete(Request $request)
    {
        return json_encode($this->controller->delete($request));
    }

    public function add_team(Request $request)
    {
        return json_encode($this->controller->add_member($request));
    }

    public function delete_team(Request $request)
    {
        return json_encode($this->controller->delete_member($request));
    }
    public function search(Request $request)
    {
        return json_encode($this->controller->search($request));
    }

    public function get(Request $request)
    {
        return json_encode($this->controller->get($request));
    }

    public function schedules(Request $request)
    {
        return json_encode($this->controller->schedules($request));
    }

    public function teams(Request $request)
    {
        return json_encode($this->controller->teams($request));
    }
}
