<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\FieldController;
use App\Models\Field;
use App\Models\Schedule;
use App\Models\Promotion;

class FieldAPITest extends TestCase
{
    // use WithoutMiddleware;
    public function setUp()
    {
        parent::setUp();
        // Artisan::call('migrate:rollback');
        // Artisan::call('migrate');
        // Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /* API Method*/
    /* --Field Login*/
    public function testFieldAPILoginInvalid()
    {
        // $model = new Field();
        // $model->username = 'relax1_a';
        // $model->password = '123456';
        // $model->name = 'Relax';
        // $model->description = 'Welcome to relax football club';
        // $model->email = 'relaxfootball@gmail.com';
        // $model->address = '19/2 A.Muang Chiang Mai 50000';
        // $model->phone_number = '0812345678';
        // $model->latitude = '18.805';
        // $model->longitude = '99.018';
        // $model->save();

        $controller = new FieldController();
        $username = 'relax1_a_invalid';
        $password = '123456';
        $result = $controller->account_login($username, $password);
        $this->assertFalse($result);
        Field::where('username', '=', 'relax1_a_invalid')->delete();
    }

    public function testFieldAPILoginValid()
    {
        $model = new Field();
        $model->username = 'relax1_a_valid';
        $model->password = '123456';
        $model->name = 'Relax';
        $model->description = 'Welcome to relax football club';
        $model->email = 'relaxfootball@gmail.com';
        $model->address = '19/2 A.Muang Chiang Mai 50000';
        $model->phone_number = '0812345678';
        $model->latitude = '18.805';
        $model->longitude = '99.018';
        $model->save();

        $controller = new FieldController();
        $username = 'relax1_a_valid';
        $password = '123456';
        $result = $controller->account_login($username, $password);
        $this->assertInstanceOf(Field::class, $result);
        Field::where('username', '=', 'relax1_a_valid')->delete();
    }

    /* --Field Register */
    public function testFieldAPIRegisterInvalidEmpty()
    {
        $controller = new FieldController();
        $username = null;
        $password = null;
        $name = 'Relax';
        $description = 'Welcome to relax football club';
        $email = 'relaxfootball@gmail.com';
        $address = '19/2 A.Muang Chiang Mai 50000';
        $phone_number = '0812345678';
        $latitude = '18.805';
        $longitude = '99.018';

        $result = $controller->account_register(null, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertFalse($result);
    }
    public function testFieldAPIRegisterInvalidAlreadyExist()
    {
        $controller = new FieldController();
        $model = new Field();
        $model->username = 'relax1_exist';
        $model->password = '123456';
        $model->name = 'Relax';
        $model->description = 'Welcome to relax football club';
        $model->email = 'relaxfootball@gmail.com';
        $model->address = '19/2 A.Muang Chiang Mai 50000';
        $model->phone_number = '0812345678';
        $model->latitude = '18.805';
        $model->longitude = '99.018';
        $model->save();

        $username = 'relax1_exist';
        $password = '123456';
        $name = 'Relax';
        $description = 'Welcome to relax football club';
        $email = 'relaxfootball@gmail.com';
        $address = '19/2 A.Muang Chiang Mai 50000';
        $phone_number = '0812345678';
        $latitude = '18.805';
        $longitude = '99.018';

        $result = $controller->account_register(null, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertFalse($result);
        Field::where('username', '=', 'relax1_exist')->delete();
    }
    public function testFieldAPIRegisterValid()
    {
        $controller = new FieldController();
        $name = 'field_register_valid';
        $description = 'field_register_valid';
        $email = 'field@register.com';
        $address = '100 Address';
        $phone_number = '0123456789';
        $username = 'field';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';

        $result = $controller->account_register($name, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertInstanceOf(Field::class, $result);
        $this->assertDatabaseHas(
            'field', [
            'name' => 'field_register_valid'
            ]
        );
        Field::where('name', '=', 'field_register_valid')->delete();
    }

    /* --Field Edit*/
    public function testFieldAPIEditInvalid()
    {
        $controller = new FieldController();
        $name = 'field_edit_valid';
        $description = 'field_edit_valid';
        $email = 'field@register.com';
        $address = '100 Address';
        $phone_number = '0123456789';
        $username = 'field';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';
        $result = $controller->account_edit($name, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertFalse($result);
    }

    public function testFieldAPIEditValid()
    {
        $model = new Field();
        $model->username = 'field_edit_valid';
        $model->password = '123456';
        $model->name = '';
        $model->description = '';
        $model->email = '';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new FieldController();
        $id = $model->id;
        $name = 'field_edit_valid';
        $description = 'field_edit_valid';
        $email = 'field@register.com';
        $address = '100 Address';
        $phone_number = '0123456789';
        $username = 'field';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';
        $result = $controller->account_edit($id, $name, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertInstanceOf(Field::class, $result);
        Field::where('name', '=', 'field_edit_valid')->delete();
    }

    /* --Field Change Password*/
    public function testFieldAPIChangePasswordInvalid()
    {
        $controller = new FieldController();

        $old_password = '1234';
        $new_password = '1234567';
        $result = $controller->account_change_password(null, $old_password, $new_password);
        $this->assertFalse($result);
    }

    public function testFieldAPIChangePasswordValid()
    {
        $model = new Field();
        $model->username = 'field_api_change_valid';
        $model->password = '123456';
        $model->name = '';
        $model->description = '';
        $model->email = '';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new FieldController();
        $id = $model->id;
        $old_password = '123456';
        $new_password = '1234567';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertTrue($result);
        Field::where('name', '=', 'field_api_change_valid')->delete();
    }

    /* --Field Forgot Password*/
    public function testFieldAPIForgotInvalid()
    {
        // $model = new Field();
        // $model->username = 'field_api_forgot_invalid';
        // $model->email = 'a@a.com';
        // $model->save();

        $controller = new FieldController();
        $username = 'Worakamon';
        $email = 'relaxfootball@gmail.com';
        $result = $controller->account_forget($username, $email);
        $this->assertFalse($result);
    }

    public function testFieldAPIForgotWrongEmailInvalid()
    {
        $model = new Field();
        $model->username = 'relax1';
        $model->email = 'a@a.com';
        $model->password = '';
        $model->name = '';
        $model->description = '';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new FieldController();
        $username = 'relax1';
        $email = 'a@b.com';
        $result = $controller->account_forget($username, $email);
        $this->assertFalse($result);
        Field::where('name', '=', 'relax1')->delete();
    }

    public function testFieldAPIForgotValid()
    {
        $model = new Field();
        $model->username = 'fieldApiForgetValid';
        $model->email = 'forget@a.com';
        $model->password = 'fieldApiForgetValid';
        $model->name = 'fieldApiForgetValid';
        $model->description = 'fieldApiForgetValid';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new FieldController();
        $username = 'fieldApiForgetValid';
        $email = 'forget@a.com';
        $result = $controller->account_forget($username, $email, true);
        $this->assertTrue($result);
        Field::where('name', '=', 'fieldApiForgetValid')->delete();
    }

    /* --Promotion Add*/
    public function testFieldAPIAddPromotionValid()
    {
        $controller = new FieldController();
        $field_id = '1';
        $title = '1 free 2';
        $price = '100';
        $description = 'Buy one get two';

        $result = $controller->promotion_add($field_id, $title, $price, $description);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'promotion', [
            'title' => '1 free 2'
            ]
        );
        Promotion::where('title', '=', '1 free 2')->delete();
    }

    public function testFieldAPIAddPromotionInvalidFieldNull()
    {
        $controller = new FieldController();
        $field_id = null;
        $title = '1 free 2';
        $price = '100';
        $description = 'Buy one get two';

        $result = $controller->promotion_add($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    public function testFieldAPIAddPromotionInvalidTitleNull()
    {
        $controller = new FieldController();
        $field_id = '1';
        $title = null;
        $price = '100';
        $description = 'Buy one get two';

        $result = $controller->promotion_add($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    public function testFieldAPIAddPromotionInvalidPriceNull()
    {
        $controller = new FieldController();
        $field_id = '1';
        $title = '1 free 2';
        $price = null;
        $description = 'Buy one get two';

        $result = $controller->promotion_add($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    public function testFieldAPIAddPromotionInvalidDescriptionNull()
    {
        $controller = new FieldController();
        $field_id = '1';
        $title = '1 free 2';
        $price = '100';
        $description = null;

        $result = $controller->promotion_add($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    /* -- Promotion Edit */
    public function testFieldAPIEditPromotionValid()
    {
        $promotion = new Promotion();
        $promotion->field_id = 10003;
        $promotion->title = 'no title';
        $promotion->price = 100;
        $promotion->description = 'Buy one get two';
        $promotion->save();

        $controller = new FieldController();

        $title = '1 free 2';
        $price = '100';
        $description = 'Buy one get two';

        $result = $controller->promotion_edit($promotion->id, $title, $price, $description);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'promotion', [
            'title' => '1 free 2'
            ]
        );
        Promotion::where('title', '=', '1 free 2')->delete();
    }

    public function testFieldAPIEditPromotionInvalidField()
    {
        $controller = new FieldController();
        $field_id = null;
        $title = '1 free 2';
        $price = '100';
        $description = 'Buy one get two';

        $result = $controller->promotion_edit($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    public function testFieldAPIEditPromotionInvalidTitle()
    {
        $controller = new FieldController();
        $field_id = '0';
        $title = null;
        $price = '100';
        $description = 'Buy one get two';

        $result = $controller->promotion_edit($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    public function testFieldAPIEditPromotionInvalidPrice()
    {
        $controller = new FieldController();
        $field_id = '0';
        $title = '1 free 2';
        $price = null;
        $description = 'Buy one get two';

        $result = $controller->promotion_edit($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    public function testFieldAPIEditPromotionInvalidDescription()
    {
        $controller = new FieldController();
        $field_id = '0';
        $title = '1 free 2';
        $price = '100';
        $description = null;

        $result = $controller->promotion_edit($field_id, $title, $price, $description);
        $this->assertFalse($result);
    }
    /* -- Schedule Reserve */
    public function testFieldAPIReserveScheduleValid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/promotions/schedule',
            'post',
            [
            'field_id' => 1,
            'date' => '2000-01-03',
            'time' => 0,
            ]
        );
        $result = $controller->schedule_reserve($request);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'schedule', [
            'date' => '2000-01-03'
            ]
        );
        Schedule::where('date', '=', '2000-01-03')->delete();
    }

    public function testFieldAPIReserveScheduleInvalid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/promotions/schedule',
            'post',
            []
        );
        $result = $controller->schedule_reserve($request);
        $this->assertFalse($result);
    }

    /* -- Schedule Delete */
    public function testFieldAPIRemoveScheduleValid()
    {
        $schedule = new Schedule();
        $schedule->field_id = 9999;
        $schedule->date = '2000-01-05';
        $schedule->time = 1;
        $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
        $schedule->save();

        $controller = new FieldController();
        $result = $controller->schedule_delete($schedule->id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing(
            'schedule', [
            'date' => '2000-01-05'
            ]
        );
        // Schedule::where('date', '=', '2000-01-03')->delete();
    }
    public function testFieldAPIRemoveScheduleInvalid()
    {
        $controller = new FieldController();
        $result = $controller->schedule_delete(null);
        $this->assertFalse($result);
    }
    /* Schedule Status */
    public function testFieldAPIScheduleConfirmValid()
    {
        $schedule = new Schedule();
        $schedule->field_id = 9999;
        $schedule->date = '2000-01-05';
        $schedule->time = 1;
        $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
        $schedule->save();

        $controller = new FieldController();
        $result = $controller->schedule_confirm($schedule->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'schedule', [
            'date' => '2000-01-05',
            'status' => 1
            ]
        );
        // Schedule::where('date', '=', '2000-01-03')->delete();
    }
    public function testFieldAPIRemoveConfirmInvalid()
    {
        $controller = new FieldController();
        $result = $controller->schedule_confirm(null);
        $this->assertFalse($result);
    }
    public function testFieldAPIScheduleShowValid()
    {
        $schedule = new Schedule();
        $schedule->field_id = 9999;
        $schedule->date = '2000-01-05';
        $schedule->time = 1;
        $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
        $schedule->save();

        $controller = new FieldController();
        $result = $controller->schedule_show($schedule->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'schedule', [
            'date' => '2000-01-05',
            'status' => 2
            ]
        );
        // Schedule::where('date', '=', '2000-01-03')->delete();
    }
    public function testFieldAPIRemoveShowInvalid()
    {
        $controller = new FieldController();
        $result = $controller->schedule_show(null);
        $this->assertFalse($result);
    }
    public function testFieldAPIScheduleNotShowValid()
    {
        $schedule = new Schedule();
        $schedule->field_id = 9999;
        $schedule->date = '2000-01-06';
        $schedule->time = 1;
        $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
        $schedule->save();

        $controller = new FieldController();
        $result = $controller->schedule_not_show($schedule->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'schedule', [
            'date' => '2000-01-06',
            'status' => 3
            ]
        );
        // Schedule::where('date', '=', '2000-01-03')->delete();
    }
    public function testFieldAPIRemoveNotShowInvalid()
    {
        $controller = new FieldController();
        $result = $controller->schedule_not_show(null);
        $this->assertFalse($result);
    }
    public function testFieldAPIScheduleCancelValid()
    {
        $schedule = new Schedule();
        $schedule->field_id = 9999;
        $schedule->date = '2000-01-07';
        $schedule->time = 1;
        $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
        $schedule->save();

        $controller = new FieldController();
        $result = $controller->schedule_cancel($schedule->id);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'schedule', [
            'date' => '2000-01-07',
            'status' => -1
            ]
        );
        // Schedule::where('date', '=', '2000-01-03')->delete();
    }
    public function testFieldAPIRemoveCancelInvalid()
    {
        $controller = new FieldController();
        $result = $controller->schedule_cancel(null);
        $this->assertFalse($result);
    }
    /* -- Search */
    public function testFieldAPISearchValid()
    {
        $controller = new FieldController();
        $keyword = 'Relax';
        $result = $controller->search($keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue(empty($result->toArray()));
    }
    public function testFieldAPISearchInvalid()
    {
        $controller = new FieldController();
        $keyword = 'ABCD';
        $result = $controller->search($keyword);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue(empty($result->toArray()));
    }
}
