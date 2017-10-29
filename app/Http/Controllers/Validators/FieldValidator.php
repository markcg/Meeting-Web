<?php
namespace App\Http\Controllers\Validators;
use Validator;
class FieldValidator
{
    public static function login_message()
    {
        return [
        'username.alpha_dash' => 'Username is incorrect format. Please use only a-z, A-Z and 0-9',
        'password.required'  => 'A message is required',
        ];
    }

    public static function change_password_message()
    {
        return [
        'required' => 'Please fill in all required fields',
        'old_password.alpha_dash' => 'Old Password is incorrect format. Please use only a-z, A-Z and 0-9',
        'old_password.min' => 'Please input 4-10 characters',
        'old_password.max' => 'Please input 4-10 characters',
        'new_password.alpha_dash' => 'New Password is incorrect format. Please use only a-z, A-Z and 0-9',
        'new_password.min' => 'Please input 4-10 characters',
        'new_password.max' => 'Please input 4-10 characters',
        're_password.alpha_dash' => 'Re Password is incorrect format. Please use only a-z, A-Z and 0-9',
        're_password.min' => 'Please input 4-10 characters',
        're_password.max' => 'Please input 4-10 characters',
        ];
    }

    public static function edit_message()
    {
        return [
        'name.alpha_spaces' => 'Field name is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'name.min' => 'Please input 4-30 characters',
        'name.max' => 'Please input 4-30 characters',
        'description.alpha_spaces'  => 'Field description is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'description.min' => 'Please input 10-30 characters',
        'description.max' => 'Please input 10-30 characters',
        'address.alpha_spaces'  => 'Field address is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'address.min' => 'Please input 20-100 characters',
        'address.max' => 'Please input 20-100 characters',
        'email.email'  => 'Email is incorrect format. Please use correct email format',
        'email.min' => 'Please input 10-30 characters',
        'email.max' => 'Please input 10-30 characters',
        'phone_number.digits' => 'Phone number is incorrect format. Please use only 0-9',
        'phone_number.max' => 'Please input 10 characters',
        'phone_number.min' => 'Please input 10 characters',
        ];
    }
    public static function edit_detail_message()
    {
        return [
        'name.alpha_spaces' => 'Field name is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'name.min' => 'Please input 4-30 characters',
        'name.max' => 'Please input 4-30 characters',
        'description.alpha_spaces'  => 'Field description is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'description.min' => 'Please input 10-30 characters',
        'description.max' => 'Please input 10-30 characters',
        'address.alpha_spaces'  => 'Field address is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'address.min' => 'Please input 20-100 characters',
        'address.max' => 'Please input 20-100 characters',
        'email.email'  => 'Email is incorrect format. Please use correct email format',
        'email.min' => 'Please input 10-30 characters',
        'email.max' => 'Please input 10-30 characters',
        'phone_number.digits' => 'Phone number is incorrect format. Please use only 0-9',
        'phone_number.max' => 'Please input 10 characters',
        'phone_number.min' => 'Please input 10 characters',
        'username.alpha_spaces' => 'Field username is incorrect format. Please use only a-z, A-Z and 0-9',
        'username.min' => 'Please input 4-10 characters',
        'username.max' => 'Please input 4-10 characters',
        ];
    }
    public static function promotion_message()
    {
        return [
        'title.unique' => 'Promotion name is already in use!',
        'title.alpha_dash' => 'Promotion name is incorrect format. Please use only a-z, A-Z and 0-9',
        'title.min' => 'Please input 4-30 characters',
        'title.max' => 'Please input 4-30 characters',
        'price.numeric' => 'Price is incorrect format. Please use only 0-9 and comma',
        'price.min' => 'Please input 2-10 characters',
        'price.max' => 'Please input 2-10 characters',
        'description.alpha_dash' => 'Promotion description is incorrect format. Please use only a-z, A-Z and 0-9',
        'description.min' => 'Please input 10-250 characters',
        'description.max' => 'Please input 10-250 characters',
        ];
    }
    public static function validate_login($username, $password)
    {
        return Validator::make(
            [
              'username' => $username,
              'password' => $password
            ], [
            'username' => 'required|alpha_dash|max:10',
            'password' => 'required',
            ],
            FieldValidator::login_message()
        );
    }
    public static function validate_change_password($old_password, $new_password, $re_password)
    {
        return Validator::make(
            [
              'old_password' => $old_password,
              'new_password' => $new_password,
              're_password' => $re_password
            ], [
            'old_password' => 'required|alpha_dash|min:4|max:10',
            'new_password' => 'required|alpha_dash|min:4|max:10',
            're_password' => 'required|alpha_dash|min:4|max:10',
            ],
            FieldValidator::change_password_message()
        );
    }
    public static function validate_edit($name, $description, $address, $email, $phone_number, $valid_old_name = false)
    {
        return Validator::make(
            [
              'name' => $name,
              'description' => $description,
              'address' => $address,
              'email' => $email,
              'phone_number' => $phone_number
            ], [
            'name' => $valid_old_name ? 'required' : 'required|unique:field,name|alpha_spaces|min:4|max:30',
            'description' => 'required|alpha_spaces|min:10|max:30',
            'address' => 'required|alpha_spaces|min:20|max:100',
            'email' => 'required|email|min:10|max:30',
            'phone_number' => 'required|digits:10|min:10|max:10'
            ],
            FieldValidator::edit_message()
        );
    }
    public static function validate_detail_edit($name, $description, $address, $email, $username, $valid_old_name = false, $valid_old_username = false)
    {
        return Validator::make(
            [
              'name' => $name,
              'description' => $description,
              'address' => $address,
              'email' => $email,
              'phone_number' => $phone_number,
              'username' => $username
            ], [
            'name' => $valid_old_name ? 'required' : 'required|unique:field,name|alpha_spaces|min:4|max:30',
            'description' => 'required|alpha_spaces|min:10|max:30',
            'address' => 'required|alpha_spaces|min:20|max:100',
            'email' => 'required|email|min:10|max:30',
            'phone_number' => 'required|digits:10|min:10|max:10',
            'username' => $valid_old_username ? 'required' : 'required|unique:field,username|alpha_spaces|min:4|max:10',
            ],
            FieldValidator::edit_message()
        );
    }
    public static function validate_promotion_add($title, $price, $decription)
    {
        return Validator::make(
            [
              'title' => $title,
              'price' => $price,
              'description' => $decription,
            ], [
            'title' => 'required|alpha_dash|min:4|max:30',
            'price' => 'required|numeric|min:10|max:9999999999',
            'description' => 'required|alpha_spaces|min:10|max:250',
            ],
            FieldValidator::promotion_message()
        );
    }
    public static function validate_promotion_edit($title, $price, $decription)
    {
        return Validator::make(
            [
              'title' => $title,
              'price' => $price,
              'description' => $decription,
            ], [
            'title' => 'required|alpha_dash|min:4|max:30',
            'price' => 'required|numeric|min:2|max:9999999999',
            'description' => 'required|alpha_dash|min:10|max:250',
            ],
            FieldValidator::promotion_message()
        );
    }
}
