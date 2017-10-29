<?php

namespace App\Http\Controllers;

use Validator;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Validators\FieldValidator;
use App\Models\Field;
use App\Models\Promotion;
use App\Models\Schedule;

class FieldController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'field', ['except' => [
            'login',
            'handle_login',
            'handle_schedule',
            'add_schedule',
            'delete_schedule',
            'confirm_schedule',
            'reserve_schedule',
            'account_login'
            ]]
        );
    }
    /* Validation Rules */
    /* Get Method */
    public function home()
    {
        return view('field.home');
    }

    public function login()
    {
        return view('field.login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('field');
        return redirect('/field/login');
    }

    public function edit(Request $request)
    {
        return view('field.edit', ['model' => $request->session()->get('field')]);
    }

    public function change_password()
    {
        return view('field.change_password');
    }

    public function promotions(Request $request)
    {
        $session = $request->session()->get('field');
        $field = Field::find($session->id);
        $promotions = $field->promotions;
        return view('field.promotion', [ "promotions" => $promotions ]);
    }

    public function add_promotion_list()
    {
        return view('field.promotion_add');
    }

    public function edit_promotion_list($id)
    {
        $promotion = Promotion::find($id);
        if (is_null($promotion)) {
            return redirect('/field/promotions');
        } else {
            return view('field.promotion_add', [ 'promotion' =>  $promotion, 'id' => $id]);
        }
    }

    public function schedule(Request $request)
    {

        $date = date('Y-m-d');
        $field = $request->session()->get('field');

        if ($request->has('search')) {
            $validator = Validator::make(
                $request->all(), [
                'search' => 'date_format:Y-m-d',
                ],
                ['search.date_format' => 'Date is invalid']
            );
            if ($validator->fails()) {
                return redirect('/field/schedule')
                    ->withErrors($validator);
            }

            $date = $request->input('search');
        }
        $reserved = [];

        for ($i = 0; $i < 7; $i++) {
            $next = DateTime::createFromFormat('Y-m-d', $date);
            $next->modify('+' . $i . ' day');
            $formatted = $next->format('Y-m-d');
            $data = Schedule::where(
                [
                ['field_id', '=', $field->id],
                ['date', '=', $formatted]
                ]
            )->orderBy('time', 'desc')->get();
            $reserved[] = $data;
        }
        return view('field.schedule', ['schedules' => $reserved]);
    }

    public function report()
    {
        $field = Field::first();
        $schedules = $field->schedules;
        return view('field.report', [ 'schedules' =>  $schedules]);
    }

    /* Post Method */
    public function handle_login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $validator = FieldValidator::validate_login($username, $password);

        if ($validator->fails()) {
            return redirect('/field/login')
                      ->withErrors($validator)
                      ->withInput();
        }

        $login = $this->account_login($username, $password);
        if (!empty($login)) {
            if($login->confirm == 1) {
                session(['field' => $login]);
                return redirect('/field');
            } else {
                return redirect('/field/login')
                ->withErrors(['Your account has not been approved by administrator'])
                ->withInput();
            }
        } else {
            return redirect('/field/login')
            ->withErrors(['Username or password is incorrect'])
            ->withInput();
        }
    }

    public function handle_change_password(Request $request)
    {
        $id = $request->input('id');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');
        $re_password = $request->input('re_password');

        $validator = FieldValidator::validate_change_password($old_password, $new_password, $re_password);
        if ($validator->fails()) {
            return redirect('/field/change-password')
                  ->withErrors($validator)
                  ->withInput();
        }

        if($request->input('new_password') !== $request->input('re_password')) {
            return redirect('/field/change-password')
            ->withErrors(['Password and Re-Password are not match.'])
            ->withInput();;
        }

        $model = $this->account_change_password($id, $old_password, $new_password);
        if (!empty($model)) {
            return redirect('/field');
        } else {
            return redirect('/field/change-password')
            ->withErrors(['Password and Re-Password are not match.'])
            ->withInput();
        }
    }

    public function handle_register(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $email= $request->input('email');
        $address = $request->input('address');
        $phone_number = $request->input('phone_number');
        $username = $request->input('username');
        $password = $request->input('password');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $model = $this->account_register($name, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude);
        if (!empty($model)) {
            return redirect('/field/login');
        } else {
            return redirect('/field/register');
        }
    }

    public function handle_edit(Request $request)
    {
        $old = Field::find($request->input('id'));
        $valid_old_name = $old->name == $request->input('name');
        $valid_old_username = $old->username == $request->input('username');

        $id = $request->input('id');
        $name = $request->input('name');
        $description = $request->input('description');
        $address = $request->input('address');
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');
        $username = $request->input('username');

        $validator = FieldValidator::validate_detail_edit($name, $description, $address, $email, $username, $valid_old_name, $valid_old_username);
        if ($validator->fails()) {
            return redirect('/admin/edit')
              ->withErrors($validator)
              ->withInput();
        }

        $model = $this->account_edit($id, $name, $description, $address, $email, $username);
        if (!empty($model)) {
            session(['field' => $model]);
            return redirect('/field/edit');
        } else {
            return redirect('/field/edit');
        }
    }
    public function add_promotion(Request $request)
    {
        $id = $request->input('field_id');
        $title = $request->input('title');
        $price = $request->input('price');
        $description = $request->input('description');

        $validator = FieldValidator::validate_promotion_add($title, $price, $description);
        if ($validator->fails()) {
            return redirect('/field/promotions/add')
                    ->withErrors($validator)
                    ->withInput();
        }

        if ($this->promotion_add($id, $title, $price, $description)) {
            return redirect('/field/promotions');
        } else {
            return redirect('/field/promotions/add')
            ->withErrors($validator)
            ->withInput();;
        }
    }

    public function edit_promotion(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $price = $request->input('price');
        $description = $request->input('description');

        $validator = FieldValidator::validate_promotion_edit($title, $price, $description);
        if ($validator->fails()) {
            return redirect('/field/promotions/edit/' . $request->input('id'))
                  ->withErrors($validator)
                  ->withInput();
        }

        if ($this->promotion_edit($id, $title, $price, $description)) {
            return redirect('/field/promotions');
        } else {
            return redirect('/field/promotions/edit/' . $request->input('id'));
        }
    }

    public function handle_schedule(Request $request)
    {
        $id = $request->input('id');
        if ($id === null) {
            $this->schedule_reserve($request);
        } else {
            $this->schedule_delete($id);
        }
        if ($request->has('search')) {
            $redirect = '/field/schedule?search=' . $request->input('search');
        } else {
            $redirect = '/field/schedule';
        }
        return redirect($redirect);
    }

    /* API Method */
    /* --Account */
    public function account_login($username, $password)
    {
        try {
            $account = Field::where(
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

    public function account_register(
        $name,
        $description,
        $email, $address,
        $phone_number,
        $username,
        $password,
        $latitude = null,
        $longitude = null
    ) {
        try {
            $account = new Field();
            $account->name = $name;
            $account->description = $description;
            $account->email = $email;
            $account->address = $address;
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

    public function account_edit(
        $id,
        $name,
        $description,
        $email, $address,
        $phone_number,
        $username,
        $password,
        $latitude = null,
        $longitude = null
    ) {
        try {
            $account = Field::find($id);
            if(is_null($account)) {
                return false;
            } else {
                $account->name = $name;
                $account->description = $description;
                $account->email = $email;
                $account->address = $address;
                $account->phone_number = $phone_number;
                $account->username = $username;
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
            $user = Field::find($id);
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
            $account = Field::where('username', '=', $username)->first();
            if(is_null($account)) {
                return false;
            } else if($account->email !== $email) {
                return false;
            }else {
                if(!$no_mail) {
                    $tempPassword = str_random(10);
                    $message = 'Your new password is ' . $tempPassword;
                    $account->password = $tempPassword;
                    Mail::raw(
                        $message, $account, function ($message) {
                            $message->from('fieldfinder.mailserver@gmail.com', 'Field Finder Forget Password');
                            $message->to($email);
                        }
                    );
                }
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /* --Promotion */
    public function promotion_add($field_id, $title, $price, $description)
    {
        try {
            $promotion = new Promotion();
            $promotion->field_id = $field_id;
            $promotion->title = $title;
            $promotion->price = $price;
            $promotion->description = $description;
            $promotion->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function promotion_edit($id, $title, $price, $description)
    {
        try {
            $promotion = Promotion::find($id);
            $promotion->title = $title;
            $promotion->price = $price;
            $promotion->description = $description;
            $promotion->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /* --Schedule */
    public function schedule_add(Request $request)
    {
        try {
            $schedule = new Schedule();
            $schedule->field_id = $request->input('field_id');
            $schedule->date = $request->input('date');
            $schedule->time = $request->input('time');
            $schedule->schedule = $request->input('schedule');
            if(!is_null($request->input('meeting_id'))
                && !empty($request->input('meeting_id'))
            ) {
                $schedule->meeting_id = $request->input('meeting_id');
            }
            if(!is_null($request->input('customer_id'))
                && !empty($request->input('customer_id'))
            ) {
                $schedule->customer_id = $request->input('customer_id');
            }
            $schedule->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function schedule_delete($id)
    {
        try {
            $schedule = Schedule::find($id);
            if (is_null($schedule)) {
                return false;
            } else {
                $schedule->delete();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function schedule_confirm($id)
    {
        try {
            $schedule = Schedule::find($id);
            if (is_null($schedule)) {
                return false;
            } else {
                $schedule->status = 1;
                $schedule->save();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function schedule_cancel($id)
    {
        try {
            $schedule = Schedule::find($id);
            if (is_null($schedule)) {
                return false;
            } else {
                $schedule->status = -1;
                $schedule->save();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function schedule_reserve(Request $request)
    {
        try {
            $schedule = new Schedule();
            $schedule->field_id = $request->input('field_id');
            $schedule->date = $request->input('date');
            $schedule->time = $request->input('time');
            if($request->has('status')) {
                $schedule->status = $request->input('status');
            }
            $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
            $schedule->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /* Mobile */
    public function search($keyword)
    {
        try {
            $result = Field::where('name', 'like', "%$keyword%")->get();
            return empty($result) ? false : $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function scheduleByName(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            $date = $request->input('date');

            $field = Field::where('name', 'like', "%$keyword%")->first();
            $next = DateTime::createFromFormat('Y-m-d', $date);
            $formatted = $next->format('Y-m-d');
            $data = Schedule::where(
                [
                ['field_id', '=', $field->id],
                ['date', '=', $formatted]
                ]
            )->orderBy('time', 'desc')->get();
            $reserved = $data;
            return empty($reserved) ? false : $reserved;
        } catch (\Exception $e) {
            return false;
        }
    }
}
