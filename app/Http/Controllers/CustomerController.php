<?php

namespace App\Http\Controllers;

use DateTime;
use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Friend;

class CustomerController extends Controller
{
    public function __construct()
    {
    }

    /* --Account */
    public function account_login($username, $password)
    {
        try {
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


    public function account_register($name, $email, $phone_number, $username, $password, $latitude, $longitude)
    {
        try {
            $account = new Customer();
            $account->name = $name;
            $account->email = $email;
            $account->phone_number = $phone_number;
            $account->username = $username;
            $account->password = $password;
            $account->latitude = $latitude;
            $account->longitude = $longitude;
            $account->save();
            return $account;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function account_edit($id, $name, $email, $phone_number, $username, $password, $latitude, $longitude)
    {
        try {
            $account = Customer::find($id);
            if(is_null($account)) {
                return false;
            } else {
                $account->name = $name;
                $account->email = $email;
                $account->phone_number = $phone_number;
                $account->username = $username;
                $account->password = $password;
                if(!is_null($latitude)
                    && !empty($latitude)
                ) {
                    $account->latitude = $latitude;
                }

                if(!is_null($longitude)
                    && !empty($longitude)
                ) {
                    $account->latitude = $longitude;
                }
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
            $user = Customer::find($id);
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

    public function account_forget($username, $email, $no_mail = false)
    {
        try {
            $account = Customer::where('username', '=', $username)->first();
            if(is_null($account)) {
                return false;
            } else if(strcmp($account->email, $email)) {
                return false;
            }else {
                if(!$no_mail) {
                    $tempPassword = str_random(10);
                    $message = 'Your new password is ' . $tempPassword;
                    $account->password = $tempPassword;
                    $account->save();
                    Mail::raw(
                        $message, function ($message) use ($account) {
                            $message->from('fieldfinder.mailserver@gmail.com', 'Field Finder Forget Password');
                            $message->to($account->email);
                        }
                    );
                }
                return true;
            }
        } catch (\Exception $e) {
            echo var_dump($e->getMessage());
            return false;
        }
    }

    /* Friend */
    public function friend_add($friend_id, $customer_id)
    {
        try {
            $friend = new Friend();
            $friend->friend_id = $friend_id;
            $friend->customer_id = $customer_id;
            $friend->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function friend_accept($id)
    {
        try {
            $friend = Friend::find($id);
            $friend->confirm = 1;
            $friend->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function friend_reject($id)
    {
        try {
            $friend = Friend::find($id);
            $friend->confirm = 2;
            $friend->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function friend_delete($id)
    {
        try {
            $friend = Friend::find($id);
            $friend->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /* Mobile */
    public function search($keyword)
    {
        try {
            $result = Customer::where('name', 'like', "%$keyword%")->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function search_new_friend($keyword, $id)
    {
        try {
            $friends = Customer::find($id)->friends()->select('friend_id')->get();
            $ids = $friends->map(
                function ($item, $key) {
                    return $item->friend_id;
                }
            )->toArray();
            array_push($ids, $id);
            $result = Customer::whereNotIn('id', $ids)
            ->where('name', 'like', "%$keyword%")
            ->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function meetings($id)
    {
        try {
            $result = Customer::find($id)->meetings()->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function teams($id)
    {
        try {
            $result = Customer::find($id)->teams()->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function friends($id)
    {
        try {
            $result = Customer::find($id)
            ->friends()
            ->select(
                'friend.id',
                'friend.customer_id',
                'friend.friend_id',
                'customer.name',
                'customer.email',
                'customer.phone_number',
                'customer.username',
                'customer.latitude',
                'customer.longitude'
            )
            ->join('customer', 'friend.friend_id', '=', 'customer.id')
            ->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function requests($id)
    {
        try {

            $result = Customer::find($id)->requests()->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function reserves($id)
    {
        try {

            $result = Customer::find($id)->reserves()->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }
}
