<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Controllers\CustomerController;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Promotion;
use App\Models\Friend;

class CustomerAPITest extends TestCase
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
    /* --Customer Login*/
    public function testCustomerAPILoginInvalid()
    {
        $model = new Customer();
        $model->username = 'Kanchanit1_invalid';
        $model->password = 'jom123';
        $model->name = 'Kanchanit Puapun';
        $model->email = 'jomsucre@gmail.com';
        $model->phone_number = '0882634644';
        $model->latitude = '18.796367351551';
        $model->longitude = '98.95334243774414';
        $model->save();

        $controller = new CustomerController();
        $username = 'worakamon';
        $password = '123456';
        $result = $controller->account_login($username, $password);
        $this->assertFalse($result);
        Customer::where('username', '=', 'Kanchanit1_invalid')->delete();
    }

    public function testCustomerAPILoginValid()
    {
        $model = new Customer();
        $model->username = 'kanchanit1_valid_login';
        $model->password = 'jom123';
        $model->name = 'Kanchanit Puapun Login Valid';
        $model->email = 'jomsucre_valid_login@gmail.com';
        $model->phone_number = '0882634644';
        $model->latitude = '18.796367351551';
        $model->longitude = '98.95334243774414';
        $model->save();

        $controller = new CustomerController();
        $username = 'kanchanit1_valid_login';
        $password = 'jom123';
        $result = $controller->account_login($username, $password);
        $this->assertInstanceOf(Customer::class, $result);
        Customer::where('username', '=', 'kanchanit1_valid_login')->delete();
    }

    /* --Customer Register */
    public function testCustomerAPIRegisterInvalid()
    {
        $controller = new CustomerController();
        $name = 'customer_test';
        $email = 'customer@register.com';
        $phone_number = '0123456789';
        $username = 'customer_test';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';

        $result = $controller->account_register(null, $email, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertFalse($result);
    }

    public function testCustomerAPIRegisterValid()
    {
        $controller = new CustomerController();
        $name = 'customer_test';
        $email = 'customer@register.com';
        $phone_number = '0123456789';
        $username = 'customer_test';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';

        $result = $controller->account_register($name, $email, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertInstanceOf(Customer::class, $result);
        $this->assertDatabaseHas(
            'customer', [
            'name' => 'customer_test'
            ]
        );
        Customer::where('name', '=', 'customer_test')->delete();
    }

    /* --Customer Edit*/
    public function testCustomerAPIEditInvalidId()
    {
        $controller = new CustomerController();
        $id = null;
        $name = 'customer_edit_valid';
        $email = 'customer@register.com';
        $phone_number = '0123456789';
        $username = 'customer';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';
        $result = $controller->account_edit($id, $name, $email, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertFalse($result);
    }
    public function testCustomerAPIEditInvalidData()
    {
        $controller = new CustomerController();
        $id = '1';
        $name = null;
        $email = null;
        $phone_number = '0123456789';
        $username = 'customer';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';
        $result = $controller->account_edit($id, $name, $email, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertFalse($result);
    }
    public function testCustomerAPIEditValid()
    {
        $model = new Customer();
        $model->username = 'Kanchanit1_edit';
        $model->password = 'jom123';
        $model->name = 'Kanchanit Puapun Edit';
        $model->email = 'jomsucre_edit@gmail.com';
        $model->phone_number = '0882634644';
        $model->latitude = '18.796367351551';
        $model->longitude = '98.95334243774414';
        $model->save();

        // $model = Customer::where('username', '=', 'kanchanit1')->get();

        $controller = new CustomerController();
        $id = $model->id;
        $username = 'Kanchanit1_edit_done';
        $password = 'jom123';
        $name = 'Kanchanit Puapun Edited';
        $email = 'jomsucre_edit@gmail.com';
        $phone_number = '0882634644';
        $latitude = '18.796367351551';
        $longitude = '98.95334243774414';
        $result = $controller->account_edit($id, $name, $email, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertInstanceOf(Customer::class, $result);
        Customer::where('name', '=', 'Kanchanit1_edit')->delete();
    }

    /* --Customer Change Password*/
    public function testCustomerAPIChangePasswordInvalidId()
    {
        $controller = new CustomerController();
        $id = null;
        $old_password = '123456';
        $new_password = '1234567';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertFalse($result);
    }
    public function testCustomerAPIChangePasswordInvalidOld()
    {
        $controller = new CustomerController();

        $id = '1';
        $old_password = null;
        $new_password = '1234567';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertFalse($result);
    }
    public function testCustomerAPIChangePasswordInvalidNew()
    {
        $controller = new CustomerController();

        $id = '1';
        $old_password = 'nong123';
        $new_password = '123456';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertFalse($result);
    }
    public function testCustomerAPIChangePasswordValid()
    {
        $model = new Customer();
        $model->username = 'Kanchanit1_change_password';
        $model->password = 'jom123';
        $model->name = 'Kanchanit Puapun';
        $model->email = 'jomsucre@gmail.com';
        $model->phone_number = '0882634644';
        $model->latitude = '18.796367351551';
        $model->longitude = '98.95334243774414';
        $model->save();

        $controller = new CustomerController();
        $id = $model->id;
        $old_password = 'jom123';
        $new_password = 'jom456';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertTrue($result);
        Customer::where('name', '=', 'Kanchanit1_change_password')->delete();
    }

    /* --Customer Forgot Password*/
    public function testCustomerAPIForgotInvalidNotExist()
    {
        // $model = new Customer();
        // $model->username = 'customer_api_forgot_invalid';
        // $model->email = 'a@a.com';
        // $model->save();

        $controller = new CustomerController();
        $username = 'worakamon';
        $email = 'jomsucre@gmail.com';
        $result = $controller->account_forget($username, $email);
        $this->assertFalse($result);
    }
    public function testCustomerAPIForgotInvalidEmail()
    {
        // $model = new Customer();
        // $model->username = 'customer_api_forgot_invalid';
        // $model->email = 'a@a.com';
        // $model->save();

        $controller = new CustomerController();
        $username = 'Kanchanit1';
        $email = 'a@b.com';
        $result = $controller->account_forget($username, $email);
        $this->assertFalse($result);
    }
    public function testCustomerAPIForgotWrongEmailInvalid()
    {
        $model = new Customer();
        $model->username = 'Kanchanit1_forgot';
        $model->password = 'jom123';
        $model->name = 'Kanchanit Puapun';
        $model->email = 'jomsucre_invalid@gmail.com';
        $model->phone_number = '0882634644';
        $model->latitude = '18.796367351551';
        $model->longitude = '98.95334243774414';
        $model->save();

        $controller = new CustomerController();
        $username = 'Kanchanit1_forgot';
        $email = 'jomsucre_invalid@gmai.com';
        $result = $controller->account_forget($username, $email);
        $this->assertFalse($result);
        Customer::where('name', '=', 'Kanchanit1_forgot')->delete();
    }

    public function testCustomerAPIForgotValid()
    {
        // $model = new Customer();
        // $model->username = 'Kanchanit1_forgot';
        // $model->password = 'jom123';
        // $model->name = 'Kanchanit Puapun';
        // $model->email = 'jomsucre@gmail.com';
        // $model->phone_number = '0882634644';
        // $model->latitude = '18.796367351551';
        // $model->longitude = '98.95334243774414';
        // $model->save();

        $controller = new CustomerController();
        $username = 'user_a';
        $email = 'messi@m.com';
        $result = $controller->account_forget($username, $email, true);
        $this->assertFalse($result);
        // Customer::where('name', '=', 'customerApiForgetValid')->delete();
    }
    /* Friend */
    public function testCustomerAPIAddFriendInvalid()
    {
        $controller = new CustomerController();
        $friend_id = null;
        $customer_id = null;
        $result = $controller->friend_add($friend_id, $customer_id);
        $this->assertFalse($result);
    }

    public function testCustomerAPIAddFriendValid()
    {
        $controller = new CustomerController();
        $friend_id = 1;
        $customer_id = 1;
        $result = $controller->friend_add($friend_id, $customer_id);
        $this->assertTrue($result);
    }

    public function testCustomerAPIFriendAcceptInvalid()
    {
        $controller = new CustomerController();
        $id = null;
        $result = $controller->friend_accept($id);
        $this->assertFalse($result);
    }

    public function testCustomerAPIFriendAcceptValid()
    {
        $controller = new CustomerController();
        $model = new Friend();
        $model->friend_id = 1;
        $model->customer_id = 1;
        $model->save();
        $result = $controller->friend_accept($model->id);
        $this->assertTrue($result);
    }

    public function testCustomerAPIFriendRejectInvalid()
    {
        $controller = new CustomerController();
        $id = null;
        $result = $controller->friend_reject($id);
        $this->assertFalse($result);
    }

    public function testCustomerAPIFriendRejectValid()
    {
        $controller = new CustomerController();
        $model = new Friend();
        $model->friend_id = 1;
        $model->customer_id = 1;
        $model->save();
        $result = $controller->friend_reject($model->id);
        $this->assertTrue($result);
    }

    public function testCustomerAPIFriendDeleteInvalid()
    {
        $controller = new CustomerController();
        $id = null;
        $result = $controller->friend_delete($id);
        $this->assertFalse($result);
    }

    public function testCustomerAPIFriendDeleteValid()
    {
        $controller = new CustomerController();
        $model = new Friend();
        $model->friend_id = 1;
        $model->customer_id = 1;
        $model->save();
        $result = $controller->friend_delete($model->id);
        $this->assertTrue($result);
    }
    /* List */
    public function testCustomerAPIGetRequestInvalid()
    {
        $controller = new CustomerController();
        $id = null;
        $result = $controller->friends_request($id);
        $this->assertEquals($result->count(), 0);
    }
}
