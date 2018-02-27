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
        $username = 'admin';
        $password = '123456';
        $result = $controller->account_login($username, $password);
        $this->assertInstanceOf(Admin::class, $result);
    }

    public function testAdminAPILoginInvalidNotExist()
    {
        $controller = new AdminController();
        $username = 'badmin';
        $password = '123456';

        $result = $controller->account_login($username, $password);
        $this->assertFalse($result);
    }

    public function testAdminAPILoginInvalidUsername()
    {
        $controller = new AdminController();
        $username = null;
        $password = '123456';

        $result = $controller->account_login($username, $password);
        $this->assertFalse($result);
    }

    public function testAdminAPILoginInvalidPassword()
    {
        $controller = new AdminController();
        $username = 'admin';
        $password = null;

        $result = $controller->account_login($username, $password);
        $this->assertFalse($result);
    }

    public function testAdminAPIEditInvalid()
    {
        $controller = new AdminController();
        $id = 1;
        $result = $controller->account_edit($id, null);
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
        $id = 1;
        $username = 'admin';
        $result = $controller->account_edit($id, $username);
        $this->assertInstanceOf(Admin::class, $result);
    }

    public function testAdminAPIChangePasswordInvalidId()
    {
        $controller = new AdminController();
        $id = null;
        $old_password = '123456';
        $new_password = '1234567';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertFalse($result);
    }
    public function testAdminAPIChangePasswordInvalidOld()
    {
        $controller = new AdminController();
        $id = '1';
        $old_password = null;
        $new_password = '1234567';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertFalse($result);
    }
    public function testAdminAPIChangePasswordInvalidNew()
    {
        $controller = new AdminController();
        $id = '1';
        $old_password = '123456';
        $new_password = null;
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertFalse($result);
    }
    public function testAdminAPIChangePasswordValid()
    {
        $controller = new AdminController();
        $id = 1;
        $old_password = '123456';
        $new_password = '1234567';
        $result = $controller->account_change_password($id, $old_password, $new_password);
        $this->assertTrue($result);
    }

    public function testAdminAPICustomerEditInvalid()
    {
        $controller = new AdminController();
        $id = null;
        $name = 'Lionel Messi';
        $email = 'messi@m.com';
        $phone_number = '0123456789';
        $result = $controller->customer_edit($id, $name, $email, $phone_number);
        $this->assertFalse($result);
    }

    public function testAdminAPICustomerEditValid()
    {
        $controller = new AdminController();
        $id = 1;
        $name = 'Lionel Messi';
        $email = 'messi@m.com';
        $phone_number = '0123456789';
        $result = $controller->customer_edit($id, $name, $email, $phone_number);
        $this->assertInstanceOf(Customer::class, $result);
    }

    public function testAdminAPIFieldEditInvalid()
    {
        $controller = new AdminController();
        $id = null;
        $name = 'Football A Field';
        $description = 'Indoor football field';
        $address = '100/20 Address to Field';
        $email = 'field_a@f.com';
        $phone_number = '0123456789';
        $result = $controller->field_edit($id, $name, $description, $address, $email, $phone_number);
        $this->assertFalse($result);
    }

    public function testAdminAPIFieldEditValid()
    {
        $controller = new AdminController();
        $id = 1;
        $name = 'Football A Field';
        $description = 'Indoor football field';
        $address = '100/20 Address to Field';
        $email = 'field_a@f.com';
        $phone_number = '0123456789';
        $result = $controller->field_edit($id, $name, $description, $address, $email, $phone_number);
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
