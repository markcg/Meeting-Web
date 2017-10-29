<?php

namespace App\Http\Controllers;

use Validator;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Validators\AdminValidator;
use App\Http\Controllers\Validators\CustomerValidator;
use App\Http\Controllers\Validators\FieldValidator;
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
        $username = $request->input('username');
        $password = $request->input('password');

        $validator = AdminValidator::validate_login($username, $password);
        if ($validator->fails()) {
            return redirect('/admin/login')
                    ->withErrors($validator)
                    ->withInput();
        }

        $login = $this->account_login($username, $password);
        if (!empty($login)) {
            session(['admin' => $login]);
            return redirect('/admin');
        } else {
            return redirect('/admin/login')
            ->withErrors(['Username or password is incorrect'])
            ->withInput();
        }
    }

    public function handle_edit(Request $request)
    {
        $old = Admin::find($request->input('id'));
        $valid_old_name = $old->username == $request->input('username');

        $id = $request->input('id');
        $username = $request->input('username');

        $validator = AdminValidator::validate_edit($username, $valid_old_name);
        if ($validator->fails()) {
            return redirect('/admin/edit')
                  ->withErrors($validator)
                  ->withInput();
        }

        $model = $this->account_edit($id, $username);
        if (!empty($model)) {
            session(['admin' => $model]);
            return redirect('/admin/edit');
        } else {
            return redirect('/admin/edit')
            ->withErrors(['There is error during saving data.'])
            ->withInput();;
        }
    }

    public function handle_change_password(Request $request)
    {
        $id = $request->input('id');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');
        $re_password = $request->input('re_password');

        $validator = AdminValidator::validate_change_password($old_password, $new_password, $re_password);
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

        $model = $this->account_change_password($id, $old_password, $new_password);
        if (!empty($model)) {
            return redirect('/admin');
        } else {
            return redirect('/admin/change-password');
        }
    }

    public function handle_edit_customer(Request $request)
    {
        $old = Customer::find($request->input('id'));
        $valid_old_name = $old->name == $request->input('name');

        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');

        $validator = CustomerValidator::validate_edit($name, $email, $phone_number, $valid_old_name);

        if ($validator->fails()) {
            return redirect('/admin/customer/' . $request->input('id'))
                    ->withErrors($validator)
                    ->withInput();
        }

        if ($this->customer_edit($id, $name, $email, $phone_number)) {
            return redirect('/admin/customer/' . $request->input('id'));
        } else {
            return redirect('/admin/customer/' . $request->input('id'));
        }
    }

    public function handle_edit_field(Request $request)
    {
        $old = Field::find($request->input('id'));
        $valid_old_name = $old->name == $request->input('name');

        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $address = $request->input('address');
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');
        $validator = FieldValidator::validate_edit($name, $description, $address, $email, $phone_number, $valid_old_name);

        if ($validator->fails()) {
            return redirect('/admin/field/' . $request->input('id'))
                  ->withErrors($validator)
                  ->withInput();
        }

        if ($this->field_edit($id, $name, $description, $address, $email, $phone_number)) {
            return redirect('/admin/field/' . $request->input('id'));
        } else {
            return redirect('/admin/field/' . $request->input('id'));
        }
    }

    /* API Method */
    /* --Account */
    public function account_login($username, $password)
    {
        try {
            $username = $username;
            $password = $password;
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

    public function account_edit($id, $username)
    {
        try {
            $account = Admin::find($id);
            if(is_null($account)) {
                return false;
            } else {
                $account->username = $username;
                $account->save();
                return $account;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function account_change_password($id, $old_password, $new_password)
    {
        try {
            $user = Admin::find($id);
            $oldPassword = $old_password;
            $newPassword = $new_password;
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

    public function customer_edit($id, $name, $email, $phone_number)
    {
        try {
            $model = Customer::find($id);
            if(is_null($model)) {
                return false;
            } else {
                $model->name = $name;
                $model->email = $email;
                $model->phone_number = $phone_number;
                $model->save();
                return $model;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function field_edit($id, $name, $description, $address, $email, $phone_number)
    {
        try {
            $model = Field::find($id);
            if(is_null($model)) {
                return false;
            } else {
                $model->name = $name;
                $model->description = $description;
                $model->email = $email;
                $model->address = $address;
                $model->phone_number = $phone_number;
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
