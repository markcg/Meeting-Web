<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TeamController;

class TeamApiController extends Controller
{
    public function __construct()
    {
        $this->controller = new TeamController();
    }

    public function create(Request $request)
    {
        $customer_id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        return json_encode($this->controller->create($customer_id, $name, $description));
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->delete($id));
    }

    public function add_member(Request $request)
    {
        $team_id = $request->input('team_id');
        $customer_id = $request->input('customer_id');
        return json_encode($this->controller->add_member($team_id, $customer_id));
    }

    public function delete_member(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->delete_member($id));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        return json_encode($this->controller->search($keyword));
    }

    public function get(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->get($id));
    }

    public function members(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->members($id));
    }

    public function meetings(Request $request)
    {
        $id = $request->input('id');
        return json_encode($this->controller->meetings($id));
    }
}
