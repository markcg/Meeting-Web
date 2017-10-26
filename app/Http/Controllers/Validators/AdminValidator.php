<?php
namespace App\Http\Controllers\Validators;

use Validator;
class AdminValidator
{

    public static function login_message()
    {
        return [
        'username.alpha_dash' => 'Username is incorrect format. Please use only a-z, A-Z and 0-9',
        'password.required'  => 'A message is required',
        ];
    }

    public static function edit_message()
    {
        return [
        'username.alpha_dash' => 'Username is incorrect format. Please use only a-z, A-Z and 0-9',
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

    public static function validate_login($request)
    {
        return Validator::make(
            $request->all(), [
            'username' => 'required|alpha_dash|max:10',
            'password' => 'required',
            ],
            AdminValidator::login_message()
        );
    }

    public static function validate_edit($request, $valid_old_username = false)
    {
        return Validator::make(
            $request->all(), [
            'username' => $valid_old_username ? 'required' : 'required|unique:admin,username|alpha_dash|max:10',
            ],
            AdminValidator::edit_message()
        );
    }

    public static function validate_change_password($request)
    {
        return Validator::make(
            $request->all(), [
            'old_password' => 'required|alpha_dash|min:4|max:10',
            'new_password' => 'required|alpha_dash|min:4|max:10',
            're_password' => 'required|alpha_dash|min:4|max:10',
            ],
            AdminValidator::change_password_message()
        );
    }
}
