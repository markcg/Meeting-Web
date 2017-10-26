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
        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /* API Method*/
    /* --Field Login*/
    public function testFieldAPILoginInvalid()
    {
        $model = new Field();
        $model->username = 'field_login_valid';
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
        $request = Request::create(
            '/login',
            'post',
            [
            'username' => 'field_login_valid',
            ]
        );
        $result = $controller->account_login($request);
        $this->assertFalse($result);
        Field::where('username', '=', 'field_login_valid')->delete();
    }

    public function testFieldAPILoginValid()
    {
        $model = new Field();
        $model->username = 'field_login_valid';
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
        $request = Request::create(
            '/login',
            'post',
            [
            'username' => 'field_login_valid',
            'password' => '123456',
            ]
        );
        $result = $controller->account_login($request);
        $this->assertInstanceOf(Field::class, $result);
        Field::where('username', '=', 'field_login_valid')->delete();
    }

    /* --Field Register */
    public function testFieldAPIRegisterInvalid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/register',
            'post',
            [
            'description' => '',
            'email' => '',
            'address' => '',
            'phone_number' => '',
            'username' => '',
            'password' => '',
            'password' => '',
            'latitude' => '',
            'longitude' => '',
            ]
        );
        $result = $controller->account_register($request);
        $this->assertFalse($result);
    }

    public function testFieldAPIRegisterValid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/register',
            'post',
            [
            'name' => 'field_register_valid',
            'description' => 'field_register_valid',
            'email' => 'field@register.com',
            'address' => '100 Address',
            'phone_number' => '0123456789',
            'username' => 'field',
            'password' => '123456',
            'latitude' => '0.1',
            'longitude' => '0.1',
            ]
        );
        $result = $controller->account_register($request);
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
        $request = Request::create(
            '/edit',
            'post',
            [
              'id' => '9999',
              'name' => 'field_edit_valid',
              'description' => 'field_edit_valid',
              'email' => 'field@register.com',
              'address' => '100 Address',
              'phone_number' => '0123456789',
              'username' => 'field',
              'password' => '123456',
              'latitude' => '0.1',
              'longitude' => '0.1',
            ]
        );
        $result = $controller->account_edit($request);
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
        $request = Request::create(
            '/edit',
            'post',
            [
            'id' => $model->id,
            'name' => 'field_edit_valid',
            'description' => 'field_edit_valid',
            'email' => 'field@register.com',
            'address' => '100 Address',
            'phone_number' => '0123456789',
            'username' => 'field',
            'password' => '123456',
            'latitude' => '0.1',
            'longitude' => '0.1',
            ]
        );
        $result = $controller->account_edit($request);
        $this->assertInstanceOf(Field::class, $result);
        Field::where('name', '=', 'field_edit_valid')->delete();
    }

    /* --Field Change Password*/
    public function testFieldAPIChangePasswordInvalid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/change-password',
            'post',
            [
              'id' => '9999',
              'old_password' => '123456',
              'new_password' => '1234567',
            ]
        );
        $result = $controller->account_change_password($request);
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
        $request = Request::create(
            '/change-password',
            'post',
            [
            'id' => $model->id,
            'old_password' => '123456',
            'new_password' => '1234567',
            ]
        );
        $result = $controller->account_change_password($request);
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
        $request = Request::create(
            '/forgot-password',
            'post',
            [
              'username' => 'field_api_forgot_invalid',
              'email' => 'a@a.com',
            ]
        );
        $result = $controller->account_forget($request);
        $this->assertFalse($result);
    }

    public function testFieldAPIForgotWrongEmailInvalid()
    {
        $model = new Field();
        $model->username = 'field_api_email_invalid';
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
        $request = Request::create(
            '/forgot-password',
            'post',
            [
              'username' => 'field_api_email_invalid',
              'email' => 'a@b.com',
            ]
        );
        $result = $controller->account_forget($request);
        $this->assertFalse($result);
        Field::where('name', '=', 'field_api_email_invalid')->delete();
    }

    public function testFieldAPIForgotValid()
    {
        // $model = new Field();
        // $model->username = 'fieldApiForgetValid';
        // $model->email = 'forget@a.com';
        // $model->password = '';
        // $model->name = '';
        // $model->description = '';
        // $model->email = '';
        // $model->address = '';
        // $model->phone_number = '';
        // $model->latitude = '';
        // $model->longitude = '';
        // $model->save();

        $controller = new FieldController();
        $request = Request::create(
            '/forgot-password',
            'post',
            [
            'username' => 'field_a',
            'email' => 'field_a@f.com',
            ]
        );
        $result = $controller->account_forget($request, true);
        $this->assertTrue($result);
        // Field::where('name', '=', 'fieldApiForgetValid')->delete();
    }

    /* --Promotion Add*/
    public function testFieldAPIAddPromotionValid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/promotions/add',
            'post',
            [
            'field_id' => 1,
            'title' => 'testFieldAPIAddPromotionValid',
            'price' => '0',
            'description' => 'testFieldAPIAddPromotionValid'
            ]
        );
        $result = $controller->promotion_add($request);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'promotion', [
            'title' => 'testFieldAPIAddPromotionValid'
            ]
        );
        Promotion::where('title', '=', 'testFieldAPIAddPromotionValid')->delete();
    }

    public function testFieldAPIAddPromotionInvalid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/promotions/add',
            'post',
            [
            'title' => 'API Add'
            ]
        );
        $result = $controller->promotion_add($request);
        $this->assertFalse($result);
    }
    /* -- Promotion Edit */
    public function testFieldAPIEditPromotionValid()
    {
        $promotion = new Promotion();
        $promotion->field_id = 10003;
        $promotion->title = 'testFieldAPIEditPromotionValid';
        $promotion->price = 0;
        $promotion->description = 'testFieldAPIEditPromotionValid';
        $promotion->save();

        $controller = new FieldController();
        $request = Request::create(
            '/promotions/edit',
            'post',
            [
            'title' => 'testFieldAPIEditPromotionValidChange',
            'price' => '0',
            'description' => 'testFieldAPIEditPromotionValid'
            ]
        );
        $result = $controller->promotion_edit($promotion->id, $request);
        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'promotion', [
            'title' => 'testFieldAPIEditPromotionValidChange'
            ]
        );
        Promotion::where('title', '=', 'testFieldAPIEditPromotionValid')->delete();
    }

    public function testFieldAPIEditPromotionInvalid()
    {
        $controller = new FieldController();
        $request = Request::create(
            '/promotions/edit',
            'post',
            [
            'title' => 'testFieldAPIEditPromotionValid',
            ]
        );
        $result = $controller->promotion_edit(null, $request);
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

    /* -- Search */
    public function testFieldAPISearchValid()
    {
        $request = Request::create(
            '/search',
            'post',
            [
            'keyword' => 'field_a',
            ]
        );
        $controller = new FieldController();
        $result = $controller->search($request);
        $this->assertInstanceOf(Collection::class, $result);
    }
    public function testFieldAPISearchInvalid()
    {
        $request = Request::create(
            '/search',
            'post',
            [
            'keyword' => '',
            ]
        );
        $controller = new FieldController();
        $result = $controller->search($request);
        $this->assertInstanceOf(Collection::class, $result);
    }
}
