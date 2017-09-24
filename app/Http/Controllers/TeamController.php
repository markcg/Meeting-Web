<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Member;
use App\Models\Meeting;

class TeamController extends Controller
{
    public function get(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Team::find($id);
            return is_null($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function create(Request $request)
    {
        try {
            $model = new Team();
            $model->customer_id = $request->input('customer_id');
            $model->name = $request->input('name');
            $model->description = $request->input('description');
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
            $result = Team::find($id);
            $result->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function add_member(Request $request)
    {
        try {
            $model = new TeamMember();
            $model->team_id = $request->input('team_id');
            $model->customer_id = $request->input('customer_id');
            $model->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete_member(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = TeamMember::find($id);
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
            $result = Team::where('name', 'like', "%$username%")->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function members(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Team::find($id)->members;
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

    public function meetings(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Team::find($id)->meetings;
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
