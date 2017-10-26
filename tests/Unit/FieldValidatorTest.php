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
        $request = Request::create(
            '/test',
            'post',
            [
            'username' => '',
            'password' => ''
            ]
        );
        $result = FieldValidator::validate_login($request);

        $username = $result->errors()->first('username');
        $password = $result->errors()->first('password');
        $this->assertTrue(!empty($username));
        $this->assertTrue(!empty($password));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testLoginTooLong()
    {
        $request = Request::create(
            '/test',
            'post',
            [
            'username' => '1234567890_',
            'password' => ''
            ]
        );
        $result = FieldValidator::validate_login($request);

        $username = $result->errors()->first('username');
        $password = $result->errors()->first('password');
        $this->assertTrue(!empty($username));
        $this->assertTrue(!empty($password));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testLoginValid()
    {
        $request = Request::create(
            '/test',
            'post',
            [
            'username' => 'field_a',
            'password' => '123456'
            ]
        );
        $result = FieldValidator::validate_login($request);

        $username = $result->errors()->first('username');
        $password = $result->errors()->first('password');
        $this->assertTrue($username === '');
        $this->assertTrue($password === '');
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
        $result = FieldValidator::validate_edit($request);

        $name = $result->errors()->first('name');
        $description = $result->errors()->first('description');
        $address = $result->errors()->first('address');
        $email = $result->errors()->first('email');
        $phone_number = $result->errors()->first('phone_number');

        $this->assertTrue(!empty($name));
        $this->assertTrue(!empty($description));
        $this->assertTrue(!empty($address));
        $this->assertTrue(!empty($email));
        $this->assertTrue(!empty($phone_number));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditInvalidCharacter()
    {
        $request = Request::create(
            '/test', 'post', [
              'name' => false,
              'description' => false,
              'address' => false,
              'email' => false,
              'phone_number' => false
            ]
        );
        $result = FieldValidator::validate_edit($request);

        $name = $result->errors()->first('name');
        $description = $result->errors()->first('description');
        $address = $result->errors()->first('address');
        $email = $result->errors()->first('email');
        $phone_number = $result->errors()->first('phone_number');

        $this->assertTrue(!empty($name));
        $this->assertTrue(!empty($description));
        $this->assertTrue(!empty($address));
        $this->assertTrue(!empty($email));
        $this->assertTrue(!empty($phone_number));
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
        $result = FieldValidator::validate_edit($request);

        $name = $result->errors()->first('name');
        $description = $result->errors()->first('description');
        $address = $result->errors()->first('address');
        $email = $result->errors()->first('email');
        $phone_number = $result->errors()->first('phone_number');

        $this->assertTrue(!empty($name));
        $this->assertTrue(!empty($description));
        $this->assertTrue(!empty($address));
        $this->assertTrue(!empty($email));
        $this->assertTrue(!empty($phone_number));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testEditNotUnique()
    {
        $unique = new Field();
        $unique->name = 'field_unique';
        $unique->description = 'description';
        $unique->address = 'address';
        $unique->email = 'a@a.com';
        $unique->phone_number = '1234567890';
        $unique->username = 'username';
        $unique->password = '123456';
        $unique->save();

        $request = Request::create(
            '/test', 'post', [
              'name' => 'field_unique',
              'description' => '1234567890',
              'address' => '12345678901234567890',
              'email' => '1234567890',
              'phone_number' => '1234567890'
            ]
        );
        $result = FieldValidator::validate_edit($request);

        $name = $result->errors()->first('name');
        $description = $result->errors()->first('description');
        $address = $result->errors()->first('address');
        $email = $result->errors()->first('email');
        $phone_number = $result->errors()->first('phone_number');

        $this->assertTrue(!empty($name));
        $this->assertTrue(empty($description));
        $this->assertTrue(empty($address));
        $this->assertTrue(empty($email));
        $this->assertTrue(empty($phone_number));
        $this->assertInstanceOf(Validator::class, $result);

        $unique->delete();
    }
    public function testEditValid()
    {
        $request = Request::create(
            '/test', 'post', [
              'name' => 'field_unique',
              'description' => '1234567890',
              'address' => '1234567890_1234567890',
              'email' => 'field@gmail.com',
              'phone_number' => '1234567890'
            ]
        );
        $result = FieldValidator::validate_edit($request);

        $name = $result->errors()->first('name');
        $description = $result->errors()->first('description');
        $address = $result->errors()->first('address');
        $email = $result->errors()->first('email');
        $phone_number = $result->errors()->first('phone_number');

        $this->assertTrue(empty($name));
        $this->assertTrue(empty($description));
        $this->assertTrue(empty($address));
        $this->assertTrue(empty($email));
        $this->assertTrue(empty($phone_number));
        $this->assertInstanceOf(Validator::class, $result);

        // $unique->delete();
    }

    /* Change Password */
    public function testChangePasswordEmpty()
    {
        $request = Request::create(
            '/test', 'post', [
            'old_password' => '',
            'new_password' => '',
            're_password' => '',
            ]
        );
        $result = FieldValidator::validate_change_password($request);

        $old_password = $result->errors()->first('old_password');
        $new_password = $result->errors()->first('new_password');
        $re_password = $result->errors()->first('re_password');

        $this->assertTrue(!empty($old_password));
        $this->assertTrue(!empty($new_password));
        $this->assertTrue(!empty($re_password));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordInvalidCharacter()
    {
        $request = Request::create(
            '/test', 'post', [
            'old_password' => '!@#$%',
            'new_password' => '!@#$%',
            're_password' => '!@#$%',
            ]
        );
        $result = FieldValidator::validate_change_password($request);

        $old_password = $result->errors()->first('old_password');
        $new_password = $result->errors()->first('new_password');
        $re_password = $result->errors()->first('re_password');

        $this->assertTrue(!empty($old_password));
        $this->assertTrue(!empty($new_password));
        $this->assertTrue(!empty($re_password));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordTooLong()
    {
        $request = Request::create(
            '/test', 'post', [
            'old_password' => '1234567890_',
            'new_password' => '1234567890_',
            're_password' => '1234567890_',
            ]
        );
        $result = FieldValidator::validate_change_password($request);

        $old_password = $result->errors()->first('old_password');
        $new_password = $result->errors()->first('new_password');
        $re_password = $result->errors()->first('re_password');

        $this->assertTrue(!empty($old_password));
        $this->assertTrue(!empty($new_password));
        $this->assertTrue(!empty($re_password));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordTooShort()
    {
        $request = Request::create(
            '/test', 'post', [
            'old_password' => '123',
            'new_password' => '123',
            're_password' => '123',
            ]
        );
        $result = FieldValidator::validate_change_password($request);

        $old_password = $result->errors()->first('old_password');
        $new_password = $result->errors()->first('new_password');
        $re_password = $result->errors()->first('re_password');

        $this->assertTrue(!empty($old_password));
        $this->assertTrue(!empty($new_password));
        $this->assertTrue(!empty($re_password));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testChangePasswordValid()
    {
        $request = Request::create(
            '/test', 'post', [
            'old_password' => '1234',
            'new_password' => '1234',
            're_password' => '1234',
            ]
        );
        $result = FieldValidator::validate_change_password($request);

        $old_password = $result->errors()->first('old_password');
        $new_password = $result->errors()->first('new_password');
        $re_password = $result->errors()->first('re_password');

        $this->assertTrue(empty($old_password));
        $this->assertTrue(empty($new_password));
        $this->assertTrue(empty($re_password));
        $this->assertInstanceOf(Validator::class, $result);
    }

    /* Promotion */
    public function testPromotionEmpty()
    {
        $request = Request::create(
            '/test', 'post', [
            'title' => '',
            'price' => '',
            'description' => '',
            ]
        );
        $result = FieldValidator::validate_promotion_add($request);

        $title = $result->errors()->first('title');
        $price = $result->errors()->first('price');
        $description = $result->errors()->first('description');

        $this->assertTrue(!empty($title));
        $this->assertTrue(!empty($price));
        $this->assertTrue(!empty($description));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionInvalidCharacter()
    {
        $request = Request::create(
            '/test', 'post', [
            'title' => '!@#$%',
            'price' => '!@#$%',
            'description' => '!@#$%',
            ]
        );
        $result = FieldValidator::validate_promotion_add($request);

        $title = $result->errors()->first('title');
        $price = $result->errors()->first('price');
        $description = $result->errors()->first('description');

        $this->assertTrue(!empty($title));
        $this->assertTrue(!empty($price));
        $this->assertTrue(!empty($description));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionTooLong()
    {
        $request = Request::create(
            '/test', 'post', [
            'title' => '123456789012345678901234567890_',
            'price' => '99999999999',
            'description' => '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890_123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890_12345678901234567890_',
            ]
        );
        $result = FieldValidator::validate_promotion_add($request);

        $title = $result->errors()->first('title');
        $price = $result->errors()->first('price');
        $description = $result->errors()->first('description');

        $this->assertTrue(!empty($title));
        $this->assertTrue(!empty($price));
        $this->assertTrue(!empty($description));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionTooShort()
    {
        $request = Request::create(
            '/test', 'post', [
            'title' => '',
            'price' => '',
            'description' => '',
            ]
        );
        $result = FieldValidator::validate_promotion_add($request);

        $title = $result->errors()->first('title');
        $price = $result->errors()->first('price');
        $description = $result->errors()->first('description');

        $this->assertTrue(!empty($title));
        $this->assertTrue(!empty($price));
        $this->assertTrue(!empty($description));
        $this->assertInstanceOf(Validator::class, $result);
    }
    public function testPromotionValid()
    {
        $request = Request::create(
            '/test', 'post', [
            'title' => 'Promotion',
            'price' => '1000',
            'description' => 'Promotion Description',
            ]
        );
        $result = FieldValidator::validate_promotion_add($request);

        $title = $result->errors()->first('title');
        $price = $result->errors()->first('price');
        $description = $result->errors()->first('description');

        $this->assertTrue(empty($title));
        $this->assertTrue(empty($price));
        $this->assertTrue(empty($description));
        $this->assertInstanceOf(Validator::class, $result);
    }
}
