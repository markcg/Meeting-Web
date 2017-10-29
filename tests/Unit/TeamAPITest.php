<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\TeamController;
use App\Models\Team;
use App\Models\TeamMember;

class TeamTest extends TestCase
{
    // use WithoutMiddleware;
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    private function createTeam()
    {
        $team = new Team();
        $team->customer_id = '1';
        $team->name = 'Team Test';
        $team->description = '2017-01-01';
        $team->save();
        return $team;
    }
    /* API Method */
    public function testTeamAPICreateValid()
    {
        $controller = new TeamController();
        $customer_id = '1';
        $name = 'Team Test';
        $description = '2017-01-01';
        $result = $controller->create($customer_id, $name, $description);
        $this->assertInstanceOf(Team::class, $result);
    }

    public function testTeamAPICreateInvalid()
    {
        $controller = new TeamController();
        $customer_id = null;
        $name = 'Team Test';
        $description = '2017-01-01';
        $result = $controller->create($customer_id, $name, $description);
        $this->assertFalse($result);
    }

    public function testTeamAPIDeleteInvalid()
    {
        $controller = new TeamController();
        $id = 9999;
        $result = $controller->delete($id);
        $this->assertFalse($result);
    }

    public function testTeamAPIDeleteValid()
    {
        $controller = new TeamController();
        $team = $this->createTeam();
        $result = $controller->delete($team->id);
        $this->assertTrue($result);
    }

    public function testTeamAPIAddMemberInvalid()
    {
        $controller = new TeamController();
        $team_id = null;
        $customer_id = null;
        $result = $controller->add_member($team_id, $customer_id);
        $this->assertFalse($result);
    }

    public function testTeamAPIAddMemberValid()
    {
        $controller = new TeamController();
        $team_id = 1;
        $customer_id = 1;
        $result = $controller->add_member($team_id, $customer_id);
        $this->assertTrue($result);
    }

    public function testTeamAPIDeleteTeamInvalid()
    {
        $controller = new TeamController();
        $id = null;
        $result = $controller->delete_member($id);
        $this->assertFalse($result);
    }

    public function testTeamAPIDeleteTeamValid()
    {
        $member = new TeamMember();
        $member->customer_id = 1;
        $member->team_id = 1;
        $member->save();

        $controller = new TeamController();
        $id = $member->id;
        $result = $controller->delete_member($id);
        $this->assertTrue($result);
    }

    public function testTeamAPISearchInvalid()
    {
        $controller = new TeamController();
        $keyword = 'ABCD';
        $result = $controller->search($keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue(empty($result->toArray()));
    }

    public function testTeamAPISearchValid()
    {
        $team = $this->createTeam();
        $team->name = 'Team Test Search';
        $team->save();

        $controller = new TeamController();
        $keyword = 'Team Test Search';
        $result = $controller->search($keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertFalse(empty($result->toArray()));
    }
}
