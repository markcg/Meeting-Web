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

class CustomerAPITest extends TestCase
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
    /* --Customer Login*/
    public function testCustomerAPILoginInvalid()
    {
        $model = new Customer();
        $model->username = 'customer_login_valid';
        $model->password = '123456';
        $model->name = '';
        $model->email = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new CustomerController();
        $username = 'customer_login_invalid';
        $password = '123456';
        $result = $controller->account_login($username, $password);
        $this->assertFalse($result);
        Customer::where('username', '=', 'customer_login_valid')->delete();
    }

    public function testCustomerAPILoginValid()
    {
        $model = new Customer();
        $model->username = 'customer_login_valid';
        $model->password = '123456';
        $model->name = '';
        $model->email = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new CustomerController();
        $username = 'customer_login_valid';
        $password = '123456';
        $result = $controller->account_login($username, $password);
        $this->assertInstanceOf(Customer::class, $result);
        Customer::where('username', '=', 'customer_login_valid')->delete();
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
    public function testCustomerAPIEditInvalid()
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

    public function testCustomerAPIEditValid()
    {
        $model = new Customer();
        $model->username = 'customer_edit_valid';
        $model->password = '123456';
        $model->name = '';
        $model->email = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new CustomerController();
        $id = $model->id;
        $name = 'customer_edit_valid';
        $email = 'customer@register.com';
        $phone_number = '0123456789';
        $username = 'customer';
        $password = '123456';
        $latitude = '0.1';
        $longitude = '0.1';
        $result = $controller->account_edit($id, $name, $email, $phone_number, $username, $password, $latitude, $longitude);
        $this->assertInstanceOf(Customer::class, $result);
        Customer::where('name', '=', 'customer_edit_valid')->delete();
    }

    /* --Customer Change Password*/
    public function testCustomerAPIChangePasswordInvalid()
    {
        $controller = new CustomerController();

        $old_password = '123456';
        $new_password = '1234567';
        $result = $controller->account_change_password(null, $old_password, $new_password);
        $this->assertFalse($result);
    }

    public function testCustomerAPIChangePasswordValid()
    {
        $model = new Customer();
        $model->username = 'customer_api_change_valid';
        $model->password = '123456';
        $model->name = '';
        $model->email = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new CustomerController();
        $id = $model->id;
        $old_password = '123456';
        $new_password = '1234567';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertTrue($result);
        Customer::where('name', '=', 'customer_api_change_valid')->delete();
    }

    /* --Customer Forgot Password*/
    public function testCustomerAPIForgotInvalid()
    {
        // $model = new Customer();
        // $model->username = 'customer_api_forgot_invalid';
        // $model->email = 'a@a.com';
        // $model->save();

        $controller = new CustomerController();
        $username = 'customer_api_forgot_invalid';
        $email = 'a@a.com';
        $result = $controller->account_forget($username, $email);
        $this->assertFalse($result);
    }

    public function testCustomerAPIForgotWrongEmailInvalid()
    {
        $model = new Customer();
        $model->username = 'customer_api_email_invalid';
        $model->email = 'a@a.com';
        $model->password = '';
        $model->name = '';
        $model->phone_number = '';
        $model->latitude = '';
        $model->longitude = '';
        $model->save();

        $controller = new CustomerController();
        $username = 'customer_api_forgot_invalid';
        $email = 'a@b.com';
        $result = $controller->account_forget($username, $email);
        $this->assertFalse($result);
        Customer::where('name', '=', 'customer_api_email_invalid')->delete();
    }

    public function testCustomerAPIForgotValid()
    {
        // $model = new Customer();
        // $model->username = 'customerApiForgetValid';
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

        $controller = new CustomerController();
        $username = 'user_a';
        $email = 'messi@m.com';
        $result = $controller->account_forget($username, $email, true);
        $this->assertTrue($result);
        // Customer::where('name', '=', 'customerApiForgetValid')->delete();
    }
}
