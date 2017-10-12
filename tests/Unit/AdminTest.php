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

class AdminTest extends TestCase
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
        $admin = new Admin();
        $admin->id = 1;
        return $admin;
    }
    public function testAdminHomeNotLogin()
    {
        $response = $this->get('admin');
        $response->assertStatus(302);
        $response->assertRedirect('admin/login');

    }

    public function testAdminHomeLogin()
    {
        $response = $this->withSession(['admin' => $this->prepareField()])->get('admin');
        $response->assertStatus(200);

    }

    public function testAdminEdit()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->get('admin/edit');
        $response->assertStatus(200);

    }

    public function testAdminCustomers()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->get('admin/customers');
        $response->assertStatus(200);

    }

    public function testAdminCustomer()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->get('admin/customer/1');
        $response->assertStatus(200);

    }

    public function testAdminCustomerEditInvalid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->post(
                'admin/customer/1',
                [
                  'id' => 1,
                  'name' => '',
                  'email' => '',
                  'phone_number' => ''
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('admin/customer/1');
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('phone_number');
    }

    public function testAdminCustomerEditValid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->post(
                'admin/customer/1',
                [
                  'id' => 1,
                  'name' => 'Lionel Messi A',
                  'email' => 'messi@m.com',
                  'phone_number' => '0123456789'
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('admin/customer/1');
        $this->assertDatabaseHas(
            'customer', [
            'name' => 'Lionel Messi A'
            ]
        );
        Customer::where('name', '=', 'Lionel Messi A')->delete();

    }

    public function testAdminFields()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->get('admin/fields');
        $response->assertStatus(200);

    }

    public function testAdminField()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->get('admin/field/1');
        $response->assertStatus(200);
    }

    public function testAdminFieldEditInvalid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->post(
                'admin/field/1',
                [
                  'id' => 1,
                  'name' => '',
                  'description' => '',
                  'address' => '',
                  'email' => '',
                  'phone_number' => ''
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('admin/field/1');
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('description');
        $response->assertSessionHasErrors('address');
        $response->assertSessionHasErrors('phone_number');
    }

    public function testAdminFieldEditValid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->post(
                'admin/field/1',
                [
                  'id' => 1,
                  'name' => 'Football A Field Change',
                  'description' => 'Indoor football field',
                  'address' => '100/20 Address to Field',
                  'email' => 'field_a@f.com',
                  'phone_number' => '0123456789'
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('admin/field/1');
        $this->assertDatabaseHas(
            'field', [
            'name' => 'Football A Field Change'
            ]
        );
        Field::where('name', '=', 'Football A Field Change')->delete();

    }

    public function testAdminFieldConfirm()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->get(
                'admin/field/confirm/1'
            );
        $response->assertStatus(302);
        $response->assertRedirect('admin/fields');
        $response->assertSessionHas('success');
    }

    public function testAdminChangePassword()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->get('admin/change-password');
        $response->assertStatus(200);

    }

    public function testAdminChangePasswordInvalid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->post(
                'admin/change-password',
                [
                  'old_password' => '',
                  'new_password' => '',
                  're_password' => '',
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('admin/change-password');
        $response->assertSessionHasErrors('old_password');
        $response->assertSessionHasErrors('new_password');
        $response->assertSessionHasErrors('re_password');
    }

    public function testAdminChangePasswordValid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareField()])
            ->post(
                'admin/change-password',
                [
                  'id' => 1,
                  'old_password' => '123456',
                  'new_password' => '1234567',
                  're_password' => '1234567',
                ]
            );
        $response->assertStatus(302);
        $response->assertRedirect('admin');
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
