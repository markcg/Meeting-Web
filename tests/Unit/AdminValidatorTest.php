<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;

use App\Http\Controllers\Validators\AdminValidator;
use Illuminate\Validation\Validator;

use App\Models\Admin;

class AdminValidationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        // Artisan::call('migrate');
        // Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }
    /* API Method */
    /* Login */
    public function testLoginEmpty()
    {
        $username = '';
        $password = '';
        $result = AdminValidator::validate_login($username, $password);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertTrue($result->errors()->has('password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testLoginTooLong()
    {
        $username = '1234567890_';
        $password = '';
        $result = AdminValidator::validate_login($username, $password);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertTrue($result->errors()->has('password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testLoginValid()
    {
        $username = 'admin';
        $password = '123456';
        $result = AdminValidator::validate_login($username, $password);

        $this->assertFalse($result->errors()->has('username'));
        $this->assertFalse($result->errors()->has('password'));
        $this->assertInstanceOf(Validator::class, $result);
    }

    /* Edit */
    public function testEditEmpty()
    {
        $username = '';
        $result = AdminValidator::validate_edit($username);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditInvalidCharacter()
    {
        $username = '!@#$%';
        $result = AdminValidator::validate_edit($username);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditTooLong()
    {
        $username = '1234567890_';
        $result = AdminValidator::validate_edit($username);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditNotUnique()
    {
        $unique = new Admin();
        $unique->username = 'admin_unique';
        $unique->password = '123456';
        $unique->save();

        $username = 'admin_unique';
        $result = AdminValidator::validate_edit($username);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertInstanceOf(Validator::class, $result);

        $unique->delete();
    }
    public function testEditValid()
    {
        $unique = new Admin();
        $unique->username = 'admin_valid';
        $unique->password = '123456';
        $unique->save();

        $username = 'admin_edit';
        $result = AdminValidator::validate_edit($username);

        $this->assertFalse($result->errors()->has('username'));
        $this->assertInstanceOf(Validator::class, $result);

        $unique->delete();
    }

    /* Change Password */
    public function testChangePasswordEmpty()
    {
        $old_password = '';
        $new_password = '';
        $re_password = '';

        $result = AdminValidator::validate_change_password($old_password, $new_password, $re_password);
        $this->assertTrue($result->errors()->has('old_password'));
        $this->assertTrue($result->errors()->has('new_password'));
        $this->assertTrue($result->errors()->has('re_password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordInvalidCharacter()
    {
        $old_password = '!@#$%';
        $new_password = '!@#$%';
        $re_password = '!@#$%';

        $result = AdminValidator::validate_change_password($old_password, $new_password, $re_password);
        $this->assertTrue($result->errors()->has('old_password'));
        $this->assertTrue($result->errors()->has('new_password'));
        $this->assertTrue($result->errors()->has('re_password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordTooLong()
    {
        $old_password = '1234567890_';
        $new_password = '1234567890_';
        $re_password = '1234567890_';

        $result = AdminValidator::validate_change_password($old_password, $new_password, $re_password);
        $this->assertTrue($result->errors()->has('old_password'));
        $this->assertTrue($result->errors()->has('new_password'));
        $this->assertTrue($result->errors()->has('re_password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordTooShort()
    {
        $old_password = '123';
        $new_password = '123';
        $re_password = '123';

        $result = AdminValidator::validate_change_password($old_password, $new_password, $re_password);
        $this->assertTrue($result->errors()->has('old_password'));
        $this->assertTrue($result->errors()->has('new_password'));
        $this->assertTrue($result->errors()->has('re_password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordValid()
    {
        $old_password = '1234';
        $new_password = '1234';
        $re_password = '1234';

        $result = AdminValidator::validate_change_password($old_password, $new_password, $re_password);
        $this->assertFalse($result->errors()->has('old_password'));
        $this->assertFalse($result->errors()->has('new_password'));
        $this->assertFalse($result->errors()->has('re_password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
}
