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
        $customer_id = $request->input('customer_id');
        $name = $request->input('name');
        $date = $request->input('date');
        $start = $request->input('start');
        $end = $request->input('end');
        $detail = $request->input('detail');
        return json_encode($this->controller->create($customer_id, $name, $date, $start, $end, $detail));
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->delete($id));
    }

    public function add_team(Request $request)
    {
        $team_id = $request->input('team_id');
        $meeting_id = $request->input('meeting_id');
        return json_encode($this->controller->add_member($team_id, $meeting_id));
    }

    public function delete_team(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->delete_member($id));
    }
    public function search(Request $request)
    {
        $id = $request->input('id');
        $keyword = $request->input('keyword');
        return json_encode($this->controller->search($id, $keyword));
    }

    public function get(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->get($id));
    }

    public function schedules(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->schedules($id));
    }

    public function teams(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->teams($id));
    }
}
