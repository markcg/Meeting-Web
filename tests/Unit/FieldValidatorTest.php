<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;

use App\Http\Controllers\Validators\FieldValidator;
use Illuminate\Validation\Validator;

use App\Models\Field;

class FieldValidationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }
    /* API Method */
    /* Login */
    public function testLoginEmpty()
    {
        $username = '';
        $password = '';
        $result = FieldValidator::validate_login($username, $password);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertTrue($result->errors()->has('password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testLoginTooLong()
    {
        $username = '1234567890_';
        $password = '';
        $result = FieldValidator::validate_login($username, $password);

        $this->assertTrue($result->errors()->has('username'));
        $this->assertTrue($result->errors()->has('password'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testLoginValid()
    {
        $username = 'field_a';
        $password = '123456';
        $result = FieldValidator::validate_login($username, $password);

        $this->assertFalse($result->errors()->has('username'));
        $this->assertFalse($result->errors()->has('password'));
        $this->assertInstanceOf(Validator::class, $result);
    }

    /* Edit */
    public function testEditEmpty()
    {
        $request = Request::create(
            '/test', 'post',
            [
              'name' => '',
              'description' => '',
              'address' => '',
              'email' => '',
              'phone_number' => ''
            ]
        );
        $name = '';
        $description = '';
        $address = '';
        $email = '';
        $phone_number = '';
        $result = FieldValidator::validate_edit($name, $description, $address, $email, $phone_number);

        $this->assertTrue($result->errors()->has('name'));
        $this->assertTrue($result->errors()->has('description'));
        $this->assertTrue($result->errors()->has('address'));
        $this->assertTrue($result->errors()->has('email'));
        $this->assertTrue($result->errors()->has('phone_number'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditInvalidCharacter()
    {
        $name = '!@#$%';
        $description = '!@#$%';
        $address = '!@#$%';
        $email = '!@#$%';
        $phone_number = '!@#$%';
        $result = FieldValidator::validate_edit($name, $description, $address, $email, $phone_number);

        $this->assertTrue($result->errors()->has('name'));
        $this->assertTrue($result->errors()->has('description'));
        $this->assertTrue($result->errors()->has('address'));
        $this->assertTrue($result->errors()->has('email'));
        $this->assertTrue($result->errors()->has('phone_number'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditTooLong()
    {
        $request = Request::create(
            '/test', 'post', [
              'name' => '123456789012345678901234567890_',
              'description' => '123456789012345678901234567890_',
              'address' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890',
              'email' => '123456789012345678901234567890_',
              'phone_number' => '1234567890_'
            ]
        );
        $name = '123456789012345678901234567890_';
        $description = '123456789012345678901234567890_';
        $address = '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890';
        $email = '123456789012345678901234567890_';
        $phone_number = '1234567890_';
        $result = FieldValidator::validate_edit($name, $description, $address, $email, $phone_number);

        $this->assertTrue($result->errors()->has('name'));
        $this->assertTrue($result->errors()->has('description'));
        $this->assertTrue($result->errors()->has('address'));
        $this->assertTrue($result->errors()->has('email'));
        $this->assertTrue($result->errors()->has('phone_number'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditNotUnique()
    {
        $unique = new Field();
        $unique->name = 'field_unique';
        $unique->description = 'description';
        $unique->address = 'address';
        $unique->email = 'field@gmail.com';
        $unique->phone_number = '1234567890';
        $unique->username = 'username';
        $unique->password = '123456';
        $unique->save();

        $name = 'field_unique';
        $description = '1234567890';
        $address = '12345678901234567890';
        $email = 'field@gmail.com';
        $phone_number = '1234567890';
        $result = FieldValidator::validate_edit($name, $description, $address, $email, $phone_number);

        $this->assertTrue($result->errors()->has('name'));
        $this->assertFalse($result->errors()->has('description'));
        $this->assertFalse($result->errors()->has('address'));
        $this->assertFalse($result->errors()->has('email'));
        $this->assertFalse($result->errors()->has('phone_number'));
        $this->assertInstanceOf(Validator::class, $result);

        $unique->delete();
    }
    public function testEditValid()
    {
        $name = 'fieldNew';
        $description = '1234567890';
        $address = '12345678901234567890';
        $email = 'field@gmail.com';
        $phone_number = '1234567890';
        $result = FieldValidator::validate_edit($name, $description, $address, $email, $phone_number);

        $this->assertFalse($result->errors()->has('name'));
        $this->assertFalse($result->errors()->has('description'));
        $this->assertFalse($result->errors()->has('address'));
        $this->assertFalse($result->errors()->has('email'));
        $this->assertFalse($result->errors()->has('phone_number'));
        $this->assertInstanceOf(Validator::class, $result);

        // $unique->delete();
    }

    /* Change Password */
    public function testChangePasswordEmpty()
    {
        $old_password = '';
        $new_password = '';
        $re_password = '';
        $result = FieldValidator::validate_change_password($old_password, $new_password, $re_password);

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
        $result = FieldValidator::validate_change_password($old_password, $new_password, $re_password);

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
        $result = FieldValidator::validate_change_password($old_password, $new_password, $re_password);

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
        $result = FieldValidator::validate_change_password($old_password, $new_password, $re_password);

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
        $result = FieldValidator::validate_change_password($old_password, $new_password, $re_password);

        $this->assertFalse($result->errors()->has('old_password'));
        $this->assertFalse($result->errors()->has('new_password'));
        $this->assertFalse($result->errors()->has('re_password'));
        $this->assertInstanceOf(Validator::class, $result);
    }

    /* Promotion */
    public function testPromotionEmpty()
    {
        $title = '';
        $price = '';
        $description = '';
        $result = FieldValidator::validate_promotion_add($title, $price, $description);

        $this->assertTrue($result->errors()->has('title'));
        $this->assertTrue($result->errors()->has('price'));
        $this->assertTrue($result->errors()->has('description'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionInvalidCharacter()
    {
        $title = '!@#$%';
        $price = '!@#$%';
        $description = '!@#$%';
        $result = FieldValidator::validate_promotion_add($title, $price, $description);

        $this->assertTrue($result->errors()->has('title'));
        $this->assertTrue($result->errors()->has('price'));
        $this->assertTrue($result->errors()->has('description'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionTooLong()
    {
        $title = '123456789012345678901234567890_';
        $price = '99999999999';
        $description = '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890_123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890_12345678901234567890_';
        $result = FieldValidator::validate_promotion_add($title, $price, $description);

        $this->assertTrue($result->errors()->has('title'));
        $this->assertTrue($result->errors()->has('price'));
        $this->assertTrue($result->errors()->has('description'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionTooShort()
    {
        $title = '1';
        $price = '1';
        $description = '1';
        $result = FieldValidator::validate_promotion_add($title, $price, $description);

        $this->assertTrue($result->errors()->has('title'));
        $this->assertTrue($result->errors()->has('price'));
        $this->assertTrue($result->errors()->has('description'));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionValid()
    {
        $title = 'Promotion';
        $price = '1000';
        $description = 'Promotion Description';
        $result = FieldValidator::validate_promotion_add($title, $price, $description);

        $this->assertFalse($result->errors()->has('title'));
        $this->assertFalse($result->errors()->has('price'));
        $this->assertFalse($result->errors()->has('description'));
        $this->assertInstanceOf(Validator::class, $result);
    }
}
