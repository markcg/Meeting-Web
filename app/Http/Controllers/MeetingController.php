<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingTeam;
use App\Models\Schedule;
use App\Models\Team;

class MeetingController extends Controller
{
    public function create(Request $request)
    {
        try {
            $model = new Meeting();
            $model->customer_id = $request->input('customer_id');
            $model->name = $request->input('name');
            $model->date = $request->input('date');
            $model->start = $request->input('start');
            $model->end = $request->input('end');
            $model->detail = $request->input('detail');
            $model->save();
            return $model;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Meeting::find($id);
            $result->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function add_team(Request $request)
    {
        try {
            $model = new MeetingTeam();
            $model->team_id = $request->input('team_id');
            $model->meeting_id = $request->input('meeting_id');
            $model->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete_team(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = MeetingTeam::find($id);
            $result->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            $result = Meeting::where('name', 'like', "%$keyword%")->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function get(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Meeting::find($id);
            return is_null($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function schedules(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Meeting::find($id)->schedules;
            return is_null($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function teams(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Meeting::find($id)->teams;
            if(is_null($result)) { return false;
            } else {
                $list = [];
                foreach($result as $item){
                    $model = $item->member;
                    if(!is_null($model)) {
                        array_push($list, $item->member);
                    }
                }
                return $list;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
