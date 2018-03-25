<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingTeam;
use App\Models\Schedule;
use App\Models\Team;
use App\Models\Field;

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
            $model->date = date('Y-m-d', strtotime($date));
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

    public function accept_team($id)
    {
        try {
            $result = MeetingTeam::find($id);
            if(!is_null($result)) {
                $result->confirm = 1;
                $result->save();
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function confirm_team($id)
    {
        try {
            $result = MeetingTeam::find($id);
            if(!is_null($result)) {
                $result->confirm = 2;
                $result->save();
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
                    $model->confirm = $item->confirm;
                    if(!is_null($model) && $model->confirm != 0) {
                        array_push($list, $item->team);
                    }
                }
                return $list;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    public function calculate_sum($coor, $count)
    {
        try{
            $calculate = $coor / $count;
            return $calculate;
        }catch(\Exception $e){
            return 0;
        }
    }
    public function total_latlng($collection)
    {
        $lat = 0.0;
        $lng = 0.0;
        try{
            foreach ($collection as $key => $value) {
                $lat += $value['lat'];
                $lng += $value['lng'];
            }
        }catch(\Exception $e){
            $lat = 0.0;
            $lng = 0.0;
        }
        return ["lat" => $lat, "lng" => $lng];
    }
    public function optimize_by_meeting($lat, $lng)
    {
        $field = $fields = Field::orderByRaw('latitude = ' . $lat . ' DESC')
        ->orderByRaw('longitude = ' . $lng . ' DESC')
        ->take(10)
        ->get();
        return $field;
    }
    public function optimize($id)
    {
        try {
            $meeting = Meeting::find($id);
            $result = $meeting->teams;
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

                $fields = Field::orderBy('latitude', 'DESC')
                ->orderBy('longitude', 'DESC')
                ->take(10)
                ->get();

                $next = DateTime::createFromFormat('Y-m-d', $meeting->date);
                $formatted = $next->format('Y-m-d');

                $available = [];
                foreach ($fields as $key => $field) {
                    $data = Schedule::where(
                        [
                        ['field_id', '=', $field->id],
                        ['date', '=', $formatted],
                        ['time', '>=', $meeting->start],
                        ['time', '<=', $meeting->end]
                        ]
                    )->orderBy('time', 'desc')->get();
                    $promotions = $field->promotions();
                    $promotion = $promotions->orderBy('id', 'desc')->first();
                    $field->promotion_name = $promotion ? $promotion->title : "";
                    $field->promotion_price = $promotion ? $promotion->price : "";
                    $field->promotion_description = $promotion ? $promotion->description : "";

                    if($data->count() == 0) {
                        array_push($available, $field);
                    }
                }

                return $available;
            }
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }
}
