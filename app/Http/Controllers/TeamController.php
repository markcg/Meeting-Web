<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Member;
use App\Models\Meeting;
use App\Models\Customer;
use App\Models\Friend;

class TeamController extends Controller
{
    public function __construct()
    {
    }
    public function get($id)
    {
        try {
            $result = Team::find($id);
            return is_null($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function create($customer_id, $name, $description)
    {
        try {
            $model = new Team();
            $model->customer_id = $customer_id;
            $model->name = $name;
            $model->description = $description;
            $model->save();
            return $model;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $result = Team::find($id);
            if(!is_null($result)) {
                $result->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function add_member($team_id, $customer_id)
    {
        try {
            $model = new TeamMember();
            $model->team_id = $team_id;
            $model->customer_id = $customer_id;
            $model->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete_member($id)
    {
        try {
            $result = TeamMember::find($id);
            if(!is_null($result)) {
                $result->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function confirm_member($id)
    {
        try {
            $result = TeamMember::find($id);
            if(!is_null($result)) {
                $result->confirm = 1;
                $result->save();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function search($keyword)
    {
        try {
            $result = Team::where('name', 'like', "%$keyword%")->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function search_new_member($keyword, $id)
    {
        try {
            $team = Team::find($id);
            $members = $team->members()->select('customer_id')->get();
            $friends = Friend::where('customer_id', '=', $team->customer_id)
            ->where('confirm', '=', '1')
            ->get();

            $friendIds = $friends->map(
                function ($item, $key) {
                    return $item->friend_id;
                }
            )->toArray();
            // echo var_dump($friendIds);
            $ids = $members->map(
                function ($item, $key) {
                    return $item->customer_id;
                }
            )->toArray();
            $result = Customer::whereNotIn('id', $ids)
            ->whereIn('id', $friendIds)
            ->where('name', 'like', "%$keyword%")
            ->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function members($id)
    {
        try {
            $result = Team::find($id)->members()->where('confirm', '=', '1')->get();
            if(is_null($result)) { return false;
            } else {
                $list = [];
                foreach($result as $item){
                    $model = $item->member;
                    $model->customer_id = $model->id;
                    $model->id = $item->id;
                    if(!is_null($model)) {
                        array_push($list, $model);
                    }
                }
                return $list;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function meetings($id)
    {
        try {
            $result = Team::find($id)->meetings;
            if(is_null($result)) { return false;
            } else {
                $list = [];
                foreach($result as $item){
                    $model = $item->meeting;
                    $model->meeting_id = $model->id;
                    $model->id = $item->id;
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
