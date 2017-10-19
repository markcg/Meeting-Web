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

class FieldTest extends TestCase
{
    // use WithoutMiddleware;
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    /* Get Method */
    private function prepareField()
    {
        $field = new Field();
        $field->id = 1;
        return $field;
    }
    public function testFieldHomeNotLogin()
    {
        $response = $this->get('field');
        $response->assertStatus(302);
        $response->assertRedirect('field/login');

    }

    public function testFieldHomeLogout()
    {
        $response = $this->withSession(['field' => $this->prepareField()])->get('field/logout');
        $response->assertStatus(302);
        $response->assertRedirect('field/login');
    }

    public function testFieldHome()
    {
        $response = $this->withSession(['field' => $this->prepareField()])->get('field');
        $response->assertStatus(200);
    }

    public function testFieldSchedule()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get('field/schedule');
        $response->assertStatus(200);

    }

    public function testFieldScheduleSearchInvalid()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get('field/schedule?search=0-0-0-');
        $response->assertStatus(302);
        $response->assertRedirect('field/schedule');
        $response->assertSessionHasErrors('search');

    }

    public function testFieldScheduleSearchValid()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get('field/schedule?search=2017-01-01');
        $response->assertStatus(200);

    }

    public function testFieldPromotion()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get('field/promotions');
        $response->assertStatus(200);

    }
    public function testFieldPromotionAdd()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get('field/promotions/add');
        $response->assertStatus(200);

    }

    public function testFieldPromotionEdit()
    {
        $promotion = new Promotion();
        $promotion->field_id = 10001;
        $promotion->title = 'testFieldPromotionEdit';
        $promotion->price = 0;
        $promotion->description = 'testFieldPromotionEdit';
        $promotion->save();

        $path = 'field/promotions/edit/' . $promotion->id;
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get($path);
        $response->assertStatus(200);

        Promotion::where('title', '=', 'testFieldPromotionEdit')->delete();
    }
    public function testFieldPromotionEditInvalid()
    {
        $path = 'field/promotions/edit/9999';
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get($path);
        $response->assertStatus(302);
        $response->assertRedirect('field/promotions');

    }
    public function testFieldReport()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get('field/report');
        $response->assertStatus(200);

    }
    public function testFieldChangePassword()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->get('field/change-password');
        $response->assertStatus(200);
    }

    /* Post Method */
    public function testFieldLoginInvalid()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/login',
                [
                'username' => '',
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/login');
        $response->assertSessionHasErrors('username');
        $response->assertSessionHasErrors('password');
    }

    public function testFieldLoginConfirmValid()
    {
        $model = new Field();
        $model->username = 'field_test';
        $model->password = '123456';
        $model->name = '';
        $model->description = '';
        $model->email = '';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->confirm = 1;
        $model->save();

        $response = $this
            // ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/login',
                [
                'username' => 'field_test',
                'password' => '123456',
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field');
        Field::where('username', '=', 'field_test')->delete();
    }
    public function testFieldLoginNotConfirmInvalid()
    {
        $model = new Field();
        $model->username = 'field_test';
        $model->password = '123456';
        $model->name = '';
        $model->description = '';
        $model->email = '';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $response = $this
            // ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/login',
                [
                'username' => 'field_test',
                'password' => '123456',
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/login');
        Field::where('username', '=', 'field_test')->delete();
    }
    public function testFieldChangePasswordInvalid()
    {
        $model = new Field();
        $model->username = 'field_change_invalid';
        $model->password = '123456';
        $model->name = '';
        $model->description = '';
        $model->email = '';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/change-password',
                [
                'id' => $model->id,
                'old_password' => '',
                'new_password' => '',
                're_password' => '',
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/change-password');
        $response->assertSessionHasErrors('old_password');
        $response->assertSessionHasErrors('new_password');
        $response->assertSessionHasErrors('re_password');
        Field::where('username', '=', 'field_change_invalid')->delete();
    }

    public function testFieldChangePasswordValid()
    {
        $model = new Field();
        $model->username = 'field_change_valid';
        $model->password = '123456';
        $model->name = '';
        $model->description = '';
        $model->email = '';
        $model->address = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/change-password',
                [
                  'id' => $model->id,
                  'old_password' => '123456',
                  'new_password' => '1234567',
                  're_password' => '1234567',
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field');
        Field::where('username', '=', 'field_change_valid')->delete();
    }

    public function testFieldPromotionAddPostInvalid()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/promotions/add',
                [
                'title' => '',
                'price' => 1,
                'description' => ''
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/promotions/add');
        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('price');
        $response->assertSessionHasErrors('description');
    }
    public function testFieldPromotionAddPostValid()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/promotions/add',
                [
                'field_id' => 1,
                'title' => 'testFieldPromotionAddPost',
                'price' => 1000,
                'description' => 'testFieldPromotionAddPost'
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/promotions');
        $this->assertDatabaseHas(
            'promotion', [
            'title' => 'testFieldPromotionAddPost'
            ]
        );
        Promotion::where('title', '=', 'testFieldPromotionAddPost')->delete();

    }

    public function testFieldPromotionEditPostInvalid()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/promotions/edit/1',
                [
                'id' => 1,
                'title' => '',
                'price' => 1,
                'description' => ''
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/promotions/edit/1');
        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('price');
        $response->assertSessionHasErrors('description');
    }

    public function testFieldPromotionEditPost()
    {
        $promotion = new Promotion();
        $promotion->field_id = 10002;
        $promotion->title = 'testFieldPromotionEditPost';
        $promotion->price = 10;
        $promotion->description = 'testFieldPromotionEditPost';
        $promotion->save();

        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/promotions/edit/' . $promotion->id,
                [
                'id' => $promotion->id,
                'title' => 'testFieldPromotionEditChange',
                'description' => $promotion->description,
                'price' => $promotion->price
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/promotions');

        Promotion::where('title', '=', 'testFieldPromotionEditPost')->delete();
        Promotion::where('title', '=', 'testFieldPromotionEditPostChange')->delete();

    }
    public function testFieldScheduleReserve()
    {
        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/schedule',
                [
                'date' => '2000-01-01',
                'time' => 1
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/schedule');
        Schedule::where('date', '=', '2000-01-01')->delete();

    }
    public function testFieldScheduleCancel()
    {
        $schedule = new Schedule();
        $schedule->field_id = 9999;
        $schedule->date = '2000-01-02';
        $schedule->time = 1;
        $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
        $schedule->save();

        $response = $this
            ->withSession(['field' => $this->prepareField()])
            ->post(
                'field/schedule',
                [
                'id' => $schedule->id,
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('field/schedule');
        $this->assertDatabaseMissing(
            'schedule', [
            'field_id' => 9999
            ]
        );

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
