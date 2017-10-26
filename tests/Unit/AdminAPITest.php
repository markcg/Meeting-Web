<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;
use App\Models\Field;
use App\Models\Customer;
use App\Models\Admin;

class AdminAPITest extends TestCase
{
    // use WithoutMiddleware;
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:rollback');
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /* API Method */
    public function testAdminAPILoginValid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/login',
            'post',
            [
            'username' => 'admin',
            'password' => '123456'
            ]
        );
        $result = $controller->account_login($request);
        $this->assertInstanceOf(Admin::class, $result);
    }

    public function testAdminAPILoginInvalid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/login',
            'post',
            [
            'username' => 'admin',
            ]
        );
        $result = $controller->account_login($request);
        $this->assertFalse($result);
    }

    public function testAdminAPIEditInvalid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/edit',
            'post',
            [
            'id' => '1',
            ]
        );
        $result = $controller->account_edit($request);
        $this->assertFalse($result);
    }

    public function testAdminAPIEditValid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/edit',
            'post',
            [
              'id' => 1,
              'username' => 'admin',
            ]
        );
        $result = $controller->account_edit($request);
        $this->assertInstanceOf(Admin::class, $result);
    }

    public function testAdminAPIChangePasswordInvalid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/change-password',
            'post',
            [
            'id' => '1',
            ]
        );
        $result = $controller->account_change_password($request);
        $this->assertFalse($result);
    }

    public function testAdminAPIChangePasswordValid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/change-password',
            'post',
            [
              'id' => 1,
              'old_password' => '123456',
              'new_password' => '123456',
            ]
        );
        $result = $controller->account_change_password($request);
        $this->assertTrue($result);
    }

    public function testAdminAPICustomerEditInvalid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/customer/1',
            'post',
            [
            'id' => '1',
            ]
        );
        $result = $controller->customer_edit($request);
        $this->assertFalse($result);
    }

    public function testAdminAPICustomerEditValid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/customer/1',
            'post',
            [
              'id' => 1,
              'name' => 'Lionel Messi',
              'email' => 'messi@m.com',
              'phone_number' => '0123456789',
            ]
        );
        $result = $controller->customer_edit($request);
        $this->assertInstanceOf(Customer::class, $result);
    }

    public function testAdminAPIFieldEditInvalid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/field/1',
            'post',
            [
            'id' => '1',
            ]
        );
        $result = $controller->field_edit($request);
        $this->assertFalse($result);
    }

    public function testAdminAPIFieldEditValid()
    {
        $controller = new AdminController();
        $request = Request::create(
            '/field/1',
            'post',
            [
              'id' => 1,
              'name' => 'Football A Field',
              'description' => 'Indoor football field',
              'address' => '100/20 Address to Field',
              'email' => 'field_a@f.com',
              'phone_number' => '0123456789'
            ]
        );
        $result = $controller->field_edit($request);
        $this->assertInstanceOf(Field::class, $result);
    }

    public function testAdminAPIFieldConfirmInvalid()
    {
        $controller = new AdminController();
        $result = $controller->field_confirm(null);
        $this->assertFalse($result);
    }

    public function testAdminAPIFieldConfirmValid()
    {
        $controller = new AdminController();
        $result = $controller->field_confirm(1);
        $this->assertInstanceOf(Field::class, $result);
    }
}
