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
    public function __construct()
    {
    }
    public function create($customer_id, $name, $date, $start, $end, $detail)
    {
        try {
            $model = new Meeting();
            $model->customer_id = $customer_id;
            $model->name = $name;
            $model->date = $date;
            $model->start = $start;
            $model->end = $end;
            $model->detail = $detail;
            $model->save();
            return $model;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $result = Meeting::find($id);
            if(is_null($result)) {
                return false;
            }
            $result->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function add_team($team_id, $meeting_id)
    {
        try {
            $model = new MeetingTeam();
            $model->team_id = $team_id;
            $model->meeting_id = $meeting_id;
            $model->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete_team($id)
    {
        try {
            $result = MeetingTeam::find($id);
            if(!is_null($result)) {
                $result->delete();
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function search($customerId, $keyword)
    {
        try {
            $result = Meeting::where('name', 'like', "%$keyword%")
            ->where('customer_id', '=', $customerId)
            ->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function search_new_team($keyword, $id)
    {
        try {
            $friends = Meeting::find($id)->teams()->select('team_id')->get();
            $ids = $friends->map(
                function ($item, $key) {
                    return $item->team_id;
                }
            )->toArray();
            $result = Team::whereNotIn('id', $ids)
            ->where('name', 'like', "%$keyword%")
            ->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function get($id)
    {
        try {
            $result = Meeting::find($id);
            return is_null($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function schedules($id)
    {
        try {
            $result = Meeting::find($id)->schedules;
            return is_null($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function teams($id)
    {
        try {
            $result = Meeting::find($id)->teams;
            if(is_null($result)) { return false;
            } else {
                $list = [];
                foreach($result as $item){
                    $model = $item->team;
                    $model->team_id = $model->id;
                    $model->id = $item->id;
                    if(!is_null($model)) {
                        array_push($list, $item->team);
                    }
                }
                return $list;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function optimize($id)
    {
        try {
            $result = Meeting::find($id)->teams;
            if(is_null($result)) {
                return false;
            } else {
                $list = [];
                $count = 0;
                $lat = 0.0;
                $lng = 0.0;
                foreach($result as $item){
                    $model = $item->team;
                    if(!is_null($model)) {
                        $members = $model->members;
                        if(!is_null($model)) {
                            foreach($members as $memberTeam){
                                $member = $memberTeam->member;
                                $lat += $member->latitude;
                                $lng += $member->longitude;
                                $count += 1;
                            }
                        }
                    }
                }

                $avgLat = $lat / $count;
                $avgLng = $lng / $count;
                echo $avgLat;
                echo '<br/>';
                echo $avgLng;
                return ;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
