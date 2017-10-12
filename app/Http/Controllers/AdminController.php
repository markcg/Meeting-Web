<?php

namespace App\Http\Controllers;

use Validator;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Field;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'admin', ['except' => [
            'login',
            'handle_login',
            'handle_customer',
            'handle_admin',
            ]]
        );
    }
    /* Get Method */
    public function home()
    {
        return view('admin.home');
    }

    public function login()
    {
        return view('admin.login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        return redirect('/admin/login');
    }

    public function edit(Request $request)
    {
        return view('admin.edit', ['model' => $request->session()->get('admin')]);
    }

    public function change_password()
    {
        return view('admin.change_password');
    }

    public function customers()
    {
        $list = Customer::orderBy('id', 'desc')->get();
        return view('admin.customers', [ "collection" => $list ]);
    }

    public function customer($id)
    {
        $model = Customer::find($id);
        if (is_null($model)) {
            return redirect('/admin/customers');
        } else {
            return view('admin.customer', [ 'model' =>  $model, 'id' => $id]);
        }
    }

    public function delete_customer($id)
    {
        $model = Customer::find($id);
        if (!is_null($model)) {
            $model->delete();
        }
        return redirect('/admin/customers');
    }

    public function fields()
    {
        $list = Field::orderBy('id', 'desc')->get();
        return view('admin.fields', [ "collection" => $list ]);
    }

    public function field($id)
    {
        $model = Field::find($id);
        if (is_null($model)) {
            return redirect('/admin/fields');
        } else {
            return view('admin.field', [ 'model' =>  $model, 'id' => $id]);
        }
    }

    public function delete_field($id)
    {
        $model = Field::find($id);
        if (!is_null($model)) {
            $model->delete();
        }
        return redirect('/admin/fields');
    }

    public function confirm_field($id)
    {
        $field = $this->field_confirm($id);
        if($field && $field->confirm == 1) {
            session(['success' => ['The new field owner account has been approving from the system']]);
        }
        return redirect('/admin/fields');
    }

    /* Post Method */
    public function handle_login(Request $request)
    {
        $login = $this->account_login($request);
        if (!empty($login)) {
            session(['admin' => $login]);
            return redirect('/admin');
        } else {
            return redirect('/admin/login');
        }
    }

    public function handle_edit(Request $request)
    {
        $model = $this->account_edit($request);
        if (!empty($model)) {
            session(['admin' => $model]);
            return redirect('/admin/edit');
        } else {
            return redirect('/admin/edit');
        }
    }

    public function change_password_rule()
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
    public function handle_change_password(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'old_password' => 'required|alpha_dash|min:4|max:10',
            'new_password' => 'required|alpha_dash|min:4|max:10',
            're_password' => 'required|alpha_dash|min:4|max:10',
            ],
            $this->change_password_rule()
        );
        if ($validator->fails()) {
            return redirect('/admin/change-password')
                ->withErrors($validator)
                ->withInput();
        }

        if($request->input('new_password') !== $request->input('re_password')) {
            return redirect('/admin/change-password')
            ->withErrors(['Password and Re-Password are not match.'])
            ->withInput();;
        }

        $model = $this->account_change_password($request);
        if (!empty($model)) {
            return redirect('/admin');
        } else {
            return redirect('/admin/change-password');
        }
    }

    public function handle_edit_customer(Request $request)
    {
        $messages = [
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
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required|string|min:4|max:30',
            'email' => 'required|email|min:10|max:30',
            'phone_number' => 'required|digits:10|min:10|max:10'
            ],
            $messages
        );

        if ($validator->fails()) {
            return redirect('/admin/customer/' . $request->input('id'))
                    ->withErrors($validator)
                    ->withInput();
        }

        if ($this->customer_edit($request)) {
            return redirect('/admin/customer/' . $request->input('id'));
        } else {
            return redirect('/admin/customer/' . $request->input('id'));
        }
    }

    public function handle_edit_field(Request $request)
    {
        $messages = [
        'name.string' => 'Field name is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'name.min' => 'Please input 4-30 characters',
        'name.max' => 'Please input 4-30 characters',
        'description.string'  => 'Field description is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'description.min' => 'Please input 10-30 characters',
        'description.max' => 'Please input 10-30 characters',
        'address.string'  => 'Field address is incorrect format. Please use only a-z, A-Z, 0-9 and space',
        'address.min' => 'Please input 20-100 characters',
        'address.max' => 'Please input 20-100 characters',
        'email.email'  => 'Email is incorrect format. Please use correct email format',
        'email.min' => 'Please input 10-30 characters',
        'email.max' => 'Please input 10-30 characters',
        'phone_number.digits' => 'Phone number is incorrect format. Please use only 0-9',
        'phone_number.max' => 'Please input 10 characters',
        'phone_number.min' => 'Please input 10 characters',
        ];
        $validator = Validator::make(
            $request->all(), [
            'name' => 'required|string|min:4|max:30',
            'description' => 'required|string|min:10|max:30',
            'address' => 'required|string|min:20|max:100',
            'email' => 'required|email|min:10|max:30',
            'phone_number' => 'required|digits:10|min:10|max:10'
            ],
            $messages
        );

        if ($validator->fails()) {
            return redirect('/admin/field/' . $request->input('id'))
                  ->withErrors($validator)
                  ->withInput();
        }

        if ($this->field_edit($request)) {
            return redirect('/admin/field/' . $request->input('id'));
        } else {
            return redirect('/admin/field/' . $request->input('id'));
        }
    }

    /* API Method */
    /* --Account */
    public function account_login(Request $request)
    {
        try {
            $username = $request->input('username');
            $password = $request->input('password');
            $account = Admin::where(
                [
                ['username', '=', $username],
                ['password', '=', $password],
                ]
            )->first();
            return empty($account) ? false : $account;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function account_edit(Request $request)
    {
        try {
            $account = Admin::find($request->input('id'));
            if(is_null($account)) {
                return false;
            } else {
                $account->username = $request->input('username');
                $account->save();
                return $account;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function account_change_password(Request $request)
    {
        try {
            $user = Admin::find($request->input('id'));
            $oldPassword = $request->input('old_password');
            $newPassword = $request->input('new_password');
            if($user->password === $oldPassword) {
                $user->password = $newPassword;
                $user->save();
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function customer_edit(Request $request)
    {
        try {
            $model = Customer::find($request->input('id'));
            if(is_null($model)) {
                return false;
            } else {
                $model->name = $request->input('name');
                $model->email = $request->input('email');
                $model->phone_number = $request->input('phone_number');
                $model->save();
                return $model;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function field_edit(Request $request)
    {
        try {
            $model = Field::find($request->input('id'));
            if(is_null($model)) {
                return false;
            } else {
                $model->name = $request->input('name');
                $model->description = $request->input('description');
                $model->email = $request->input('email');
                $model->address = $request->input('address');
                $model->phone_number = $request->input('phone_number');
                $model->save();
                return $model;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function field_confirm($id)
    {
        try {
            $model = Field::find($id);
            if(is_null($model)) {
                return false;
            } else {
                $model->confirm = ($model->confirm == 0 || $model->confirm == 2) ? 1 : 2;
                $model->save();
                return $model;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
