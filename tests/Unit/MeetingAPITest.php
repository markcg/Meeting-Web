<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\MeetingController;
use App\Models\Meeting;
use App\Models\MeetingTeam;

class MeetingTest extends TestCase
{
    // use WithoutMiddleware;
    public function setUp()
    {
        parent::setUp();
        // Artisan::call('migrate:rollback');
        // Artisan::call('migrate');
        // Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    private function createMeeting()
    {
        $meeting = new Meeting();
        $meeting->customer_id = '1';
        $meeting->name = 'Meeting Test';
        $meeting->date = '2017-01-01';
        $meeting->start = '01:00';
        $meeting->end = '02:00';
        $meeting->detail = 'Meeting Test';
        $meeting->save();
        return $meeting;
    }
    /* API Method */
    public function testMeetingAPICreateValid()
    {
        $controller = new MeetingController();
        $customer_id = 1;
        $name = 'Meeting Test';
        $date = '2017-01-01';
        $start = '01:00';
        $end = '02:00';
        $detail = 'Meeting Test';

        $result = $controller->create($customer_id, $name, $date, $start, $end, $detail);
        $this->assertInstanceOf(Meeting::class, $result);
    }

    public function testMeetingAPICreateInvalid()
    {
        $controller = new MeetingController();

        $customer_id = null;
        $name = 'Meeting Test';
        $date = '2017-01-01';
        $start = '01:00';
        $end = '02:00';
        $detail = 'Meeting Test';

        $result = $controller->create($customer_id, $name, $date, $start, $end, $detail);
        $this->assertFalse($result);
    }

    public function testMeetingAPIDeleteInvalid()
    {
        $controller = new MeetingController();
        $id = null;
        $result = $controller->delete($id);
        $this->assertFalse($result);
    }

    public function testMeetingAPIDeleteValid()
    {
        $controller = new MeetingController();

        $meeting = $this->createMeeting();
        $id = $meeting->id;

        $result = $controller->delete($id);
        $this->assertTrue($result);
    }

    public function testMeetingAPIAddTeamInvalid()
    {
        $controller = new MeetingController();

        $team_id = null;
        $meeting_id = null;

        $result = $controller->add_team($team_id, $meeting_id);
        $this->assertFalse($result);
    }

    public function testMeetingAPIAddTeamValid()
    {
        $controller = new MeetingController();

        $team_id = 1;
        $meeting_id = 1;

        $result = $controller->add_team($team_id, $meeting_id);
        $this->assertTrue($result);
    }

    public function testMeetingAPIDeleteTeamInvalid()
    {
        $controller = new MeetingController();
        $id = null;
        $result = $controller->delete_team($id);
        $this->assertFalse($result);
    }

    public function testMeetingAPIDeleteTeamValid()
    {
        $meetingTeam = new MeetingTeam();
        $meetingTeam->meeting_id = 1;
        $meetingTeam->team_id = 1;
        $meetingTeam->save();

        $controller = new MeetingController();
        $id = $meetingTeam->id;
        $result = $controller->delete_team($id);
        $this->assertTrue($result);
    }

    public function testMeetingAPISearchInvalidNotExist()
    {
        $controller = new MeetingController();
        $id = '1';
        $keyword = 'Meeting Test Search';
        $result = $controller->search($id, $keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue(empty($result->toArray()));
    }
    public function testMeetingAPISearchInvalidEmpty()
    {
        $controller = new MeetingController();
        $id = '1';
        $keyword = null;
        $result = $controller->search($id, $keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertFalse(empty($result->toArray()));
    }
    public function testMeetingAPISearchValid()
    {
        $meeting = $this->createMeeting();
        $meeting->customer_id = 1;
        $meeting->name = 'Meeting Test Search';
        $meeting->save();
        $controller = new MeetingController();

        $id = 1;
        $keyword = 'Meeting Test Search';
        $result = $controller->search($id, $keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertFalse(empty($result->toArray()));
    }
    public function testMeetingAPIAcceptTeamValid()
    {
        $model = new MeetingTeam();
        $model->team_id = 1;
        $model->meeting_id = 1;
        $model->save();

        $controller = new MeetingController();
        $result = $controller->accept_team($model->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'meeting_team', [
            'team_id' => 1,
            'meeting_id' => 1,
            'confirm' => 1
            ]
        );
        // Schedule::where('date', '=', '2000-01-03')->delete();
    }
    public function testMeetingAPIAcceptTeamInvalid()
    {
        $controller = new MeetingController();
        $result = $controller->accept_team(null);
        $this->assertFalse($result);
    }
    public function testMeetingAPIConfirmTeamValid()
    {
        $model = new MeetingTeam();
        $model->team_id = 1;
        $model->meeting_id = 1;
        $model->save();

        $controller = new MeetingController();
        $result = $controller->confirm_team($model->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'meeting_team', [
            'team_id' => 1,
            'meeting_id' => 1,
            'confirm' => 2
            ]
        );
        // Schedule::where('date', '=', '2000-01-03')->delete();
    }
    public function testMeetingAPIConfirmTeamInvalid()
    {
        $controller = new MeetingController();
        $result = $controller->confirm_team(null);
        $this->assertFalse($result);
    }

    /* Optimize */
    public function testCalculateSumInvalid()
    {
        $controller = new MeetingController();
        $result = $controller->calculate_sum(0, 0);
        $this->assertEquals(0, $result);
    }

    public function testCalculateSumValidLatitude()
    {
        $controller = new MeetingController();
        $result = $controller->calculate_sum(93.970715, 5);
        $this->assertEquals(18.794143, $result);
    }

    public function testCalculateSumValidLongitude()
    {
        $controller = new MeetingController();
        $result = $controller->calculate_sum(494.896315, 5);
        $this->assertEquals(98.979263, $result);
    }

    /* Sum lat lng */
    public function testTotalLatLngInvalid()
    {
        $collection = [
          ["lat" => 18.794143],
          ["lat" => 18.794143, "lng" => 98.969263],
          ["lat" => 18.794143, "lng" => 98.969263],
          ["lat" => 18.794143, "lng" => 98.969263],
          ["lat" => 18.794143, "lng" => 98.969263]
        ];
        $controller = new MeetingController();
        $result = $controller->total_latlng($collection);
        $this->assertEquals(0, $result["lat"]);
        $this->assertEquals(0, $result["lng"]);
    }
    public function testTotalLatLngValid()
    {
        $collection = [
          ["lat" => 18.794143, "lng" => 98.969263],
          ["lat" => 18.794143, "lng" => 98.969263],
          ["lat" => 18.794143, "lng" => 98.969263],
          ["lat" => 18.794143, "lng" => 98.969263],
          ["lat" => 18.794143, "lng" => 98.969263]
        ];
        $controller = new MeetingController();
        $result = $controller->total_latlng($collection);
        $this->assertEquals(93.970715, $result["lat"]);
        $this->assertEquals(494.846315, $result["lng"]);
    }

    /* Optimize By Calculation */
    public function testOptimizeValidDefault()
    {
        $controller = new MeetingController();
        $result = $controller->optimize_by_meeting(0, 0);
        $collection = $result->toArray();
        $this->assertContains("Football A Field", $collection[0]['name']);
    }
    public function testOptimizeValidNearFieldE()
    {
        $controller = new MeetingController();
        $result = $controller->optimize_by_meeting(18.793143, 98.969263);
        $collection = $result->toArray();
        $this->assertContains("Football E Field", $collection[0]['name']);
    }
}
