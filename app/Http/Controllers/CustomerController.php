<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct()
    {
    }

    /* --Account */
    public function account_login(Request $request)
    {
        try {
            $username = $request->input('username');
            $password = $request->input('password');
            $account = Customer::where(
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


    public function account_register(Request $request)
    {
        try {
            $account = new Customer();
            $account->name = $request->input('name');
            $account->email = $request->input('email');
            $account->phone_number = $request->input('phone_number');
            $account->username = $request->input('username');
            $account->password = $request->input('password');
            $account->latitude = $request->input('latitude');
            $account->longitude = $request->input('longitude');
            $account->save();
            return $account;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function account_edit(Request $request)
    {
        try {
            $account = Customer::find($request->input('id'));
            if(is_null($account)) {
                return false;
            } else {
                $account->name = $request->input('name');
                $account->email = $request->input('email');
                $account->phone_number = $request->input('phone_number');
                $account->username = $request->input('username');
                $account->password = $request->input('password');
                if(!is_null($request->input('latitude'))
                    && !empty($request->input('latitude'))
                ) {
                    $account->latitude = $request->input('latitude');
                }

                if(!is_null($request->input('longitude'))
                    && !empty($request->input('longitude'))
                ) {
                    $account->latitude = $request->input('longitude');
                }
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
            $user = Customer::find($request->input('customer_id'));
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

    public function account_forget(Request $request)
    {
        try {
            $account = Customer::where('username', '=', $request->input('username'))->first();
            if(is_null($account)) {
                return false;
            } else {
                $tempPassword = str_random(10);
                $message = 'Your new password is ' . $tempPassword;
                Mail::raw(
                    $message, $account, function ($message) {
                        $message->from('fieldfinder.mailserver@gmail.com', 'Field Finder Forget Password');
                        $message->to($account->email);
                    }
                );
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /* Friend */
    public function friend_add(Request $request)
    {
        try {
            $friend = new Friend();
            $friend->friend_id = $request->input('friend_id');
            $friend->customer_id = $request->input('customer_id');
            $friend->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function friend_accept(Request $request)
    {
        try {
            $friend = Friend::find($request->input('id'));
            $friend->confirm = 1;
            $friend->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function friend_reject(Request $request)
    {
        try {
            $friend = Friend::find($request->input('id'));
            $friend->confirm = 2;
            $friend->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function friend_delete(Request $request)
    {
        try {
            $friend = Friend::find($request->input('id'));
            $friend->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /* Mobile */
    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            $result = Customer::where('name', 'like', "%$username%")->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function meetings(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Customer::find($id)->meetings();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function teams(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Customer::find($id)->teams();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function friends(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Customer::find($id)->friends();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function requests(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Customer::find($id)->requests();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function reserves(Request $request)
    {
        try {
            $id = $request->input('id');
            $result = Customer::find($id)->reserves();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
}
