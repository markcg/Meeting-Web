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
        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
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

    public function testMeetingAPISearchInvalid()
    {
        $controller = new MeetingController();
        $id = null;
        $keyword = 'Meeting Test Search';
        $result = $controller->search($id, $keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue(empty($result->toArray()));
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
}
