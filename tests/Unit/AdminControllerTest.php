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

class AdminControllerTest extends TestCase
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
    private function prepareAdmin()
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
        $response = $this->withSession(['admin' => $this->prepareAdmin()])->get('admin');
        $response->assertStatus(200);

    }

    public function testAdminEdit()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareAdmin()])
            ->get('admin/edit');
        $response->assertStatus(200);

    }

    public function testAdminCustomers()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareAdmin()])
            ->get('admin/customers');
        $response->assertStatus(200);

    }

    public function testAdminCustomer()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareAdmin()])
            ->get('admin/customer/1');
        $response->assertStatus(200);

    }

    public function testAdminCustomerEditInvalid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareAdmin()])
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
            ->withSession(['admin' => $this->prepareAdmin()])
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
            ->withSession(['admin' => $this->prepareAdmin()])
            ->get('admin/fields');
        $response->assertStatus(200);

    }

    public function testAdminField()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareAdmin()])
            ->get('admin/field/1');
        $response->assertStatus(200);
    }

    public function testAdminFieldEditInvalid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareAdmin()])
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
            ->withSession(['admin' => $this->prepareAdmin()])
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
            ->withSession(['admin' => $this->prepareAdmin()])
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
            ->withSession(['admin' => $this->prepareAdmin()])
            ->get('admin/change-password');
        $response->assertStatus(200);

    }

    public function testAdminChangePasswordInvalid()
    {
        $response = $this
            ->withSession(['admin' => $this->prepareAdmin()])
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
            ->withSession(['admin' => $this->prepareAdmin()])
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
}
