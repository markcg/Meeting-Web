<?php
namespace App\Http\Controllers\Validators;
use Validator;
class CustomerValidator
{

    public static function edit_message()
    {
        return [
        'name.string' => 'Name is incorrect format. Please use only a-z, A-Z and one space',
        'name.min' => 'Please input 4-30 characters',
        'name.max' => 'Please input 4-30 characters',
        'email.email'  => 'Email is incorrect format. Please use correct email format',
        'email.min' => 'Please input 10-30 characters',
        'email.max' => 'Please input 10-30 characters',
        'phone_number.digits' => 'Phone number is incorrect format. Please use only 0-9',
        'phone_number.max' => 'Please input 10 characters',
        'phone_number.min' => 'Please input 10 characters',
        ];
    }

    public static function validate_edit($request, $valid_old_name)
    {
        return Validator::make(
            $request->all(), [
            'name' => $valid_old_name ? 'required' : 'required|unique:customer,name|string|min:4|max:30',
            'email' => 'required|email|min:10|max:30',
            'phone_number' => 'required|digits:10|min:10|max:10'
            ],
            CustomerValidator::edit_message()
        );
    }
}
