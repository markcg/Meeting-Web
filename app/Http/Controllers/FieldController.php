<?php

namespace App\Http\Controllers;

use Validator;
use DateTime;
use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Validators\FieldValidator;
use App\Models\Field;
use App\Models\Promotion;
use App\Models\Schedule;
use App\Models\Customer;

class FieldController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'field', ['except' => [
            'login',
            'register',
            'forgot_password',
            'handle_login',
            'handle_schedule',
            'handle_register',
            'handle_forgot_password',
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
    public function register()
    {
        return view('field.register');
    }
    public function forgot_password()
    {
        return view('field.forgot_password');
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

    public function report(Request $request)
    {
        $session = $request->session()->get('field');
        $field = Field::find($session->id);
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
        $repassword = $request->input('repassword');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if($request->input('repassword') !== $request->input('password')) {
            return redirect('/field/register')
            ->withErrors(['Password and Re-Password are not match.'])
            ->withInput();;
        }
        $validator = FieldValidator::validate_detail_edit($name, $password, $description, $address, $email, $phone_number, $username);
        if ($validator->fails()) {
            return redirect('/field/register')
              ->withErrors($validator)
              ->withInput();
        }

        $model = $this->account_register($name, $description, $email, $address, $phone_number, $username, $password, $latitude, $longitude);
        if (!empty($model)) {
            $request->session()->put('success', ['Register successful, Please login']);
            return redirect('/field/login');
        } else {
            return redirect('/field/register');
        }
    }

    public function handle_forgot_password(Request $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');

        $validator = FieldValidator::validate_forgot_password($username, $email);
        if ($validator->fails()) {
            return redirect('/field/forgot-password')
              ->withErrors($validator)
              ->withInput();
        }

        $result = $this->account_forget($username, $email);
        if ($result) {
            $request->session()->put('success', ['Please check your email address inbox, ' . $email]);
            return redirect('/field/login');
        } else {
            return redirect('/field/forgot-password');
        }
    }

    public function handle_edit(Request $request)
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
            return redirect('/field/edit')
              ->withErrors($validator)
              ->withInput();
        }

        $model = $this->account_edit(
            $id,
            $name,
            $description,
            $email,
            $address,
            $phone_number
        );
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
        $status = $request->input('status');

        if ($id === null) {
            $this->schedule_reserve($request);
        } if($status !== null && $status == 0) {
            $this->schedule_confirm($id);
        } if($status !== null && $status == 2) {
            $this->schedule_confirm($id);
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

    public function handle_report_schedule_confirm(Request $request)
    {
        $id = $request->input('id');
        $this->schedule_confirm($id);
        $redirect = '/field/report';
        return redirect($redirect);
    }

    public function handle_report_schedule_cancel(Request $request)
    {
        $id = $request->input('id');
        $this->schedule_cancel($id);
        $redirect = '/field/report';
        return redirect($redirect);
    }

    public function handle_report_schedule_show(Request $request)
    {
        $id = $request->input('id');
        $this->schedule_show($id);
        $redirect = '/field/report';
        return redirect($redirect);
    }

    public function handle_report_schedule_not_show(Request $request)
    {
        $id = $request->input('id');
        $this->schedule_not_show($id);
        $redirect = '/field/report';
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
        $email,
        $address,
        $phone_number,
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
    public function schedule_show($id)
    {
        try {
            $schedule = Schedule::find($id);
            if (is_null($schedule)) {
                return false;
            } else {
                $schedule->status = 2;
                $schedule->save();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function schedule_not_show($id)
    {
        try {
            $schedule = Schedule::find($id);
            if (is_null($schedule)) {
                return false;
            } else {
                $schedule->status = 3;
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
            $schedule->date = date('Y-m-d', strtotime($request->input('date')));
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
    public function schedule_reserve_by_user(Request $request)
    {
        try {
            $customer = Customer::find($request->input('customer_id'));
            $schedule = new Schedule();
            $schedule->field_id = $request->input('field_id');
            $schedule->date = date('Y-m-d', strtotime($request->input('date')));
            $schedule->time = $request->input('time');
            $schedule->customer_id = $request->input('customer_id');

            if($request->has('status')) {
                $schedule->status = $request->input('status');
            }
            $schedule->schedule = 'Reserve by ' . $customer->name . ' at ' . date('Y-m-d H:i:s') . ' , Contact: ' . $customer->phone_number;
            $schedule->save();
            return true;
        } catch (\Exception $e) {
            echo var_dump($e);
            return $e;
        }
    }
    public function schedule_reserve_by_meeting(Request $request)
    {
        try {
            $meeting = Meeting::find($request->input('meeting_id'));

            $schedule = new Schedule();
            $schedule->field_id = $request->input('field_id');
            $schedule->date = date('Y-m-d', strtotime($request->input('date')));
            $schedule->time = $request->input('time');
            $schedule->meeting_id = $request->input('meeting_id');

            if($request->has('status')) {
                $schedule->status = $request->input('status');
            }
            $schedule->schedule = 'Reserve by ' . $meeting->name . ' at ' . date('Y-m-d H:i:s') . ' , Contact: ' . $meeting->customer->phone_number;
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

    public function scheduleById($id, $date)
    {
        try {
            $field = Field::find($id);
            // $next = DateTime::createFromFormat('Y-m-d', $date);
            $formatted = date('Y-m-d', strtotime($date));
            $data = Schedule::where(
                [
                ['field_id', '=', $field->id],
                ['date', '=', $formatted]
                ]
            )->orderBy('time', 'desc')->get();
            $reserved = !is_null($field) && !is_null($data) ? $data : [];
            return empty($reserved) && is_null($field) ? false : [
              "reserved" => $reserved,
              "field" => $field
            ];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function scheduleByName($keyword, $date)
    {
        try {
            $field = Field::where('name', 'like', "%$keyword%")->first();
            // $next = DateTime::createFromFormat('Y-m-d', $date);
            $formatted = date('Y-m-d', strtotime($date));
            $data = Schedule::where(
                [
                ['field_id', '=', $field->id],
                ['date', '=', $formatted]
                ]
            )->orderBy('time', 'desc')->get();
            $reserved = !is_null($field) && !is_null($data) ? $data : [];
            return empty($reserved) && is_null($field) ? false : [
              "reserved" => $reserved,
              "field" => $field
            ];
        } catch (\Exception $e) {
            return false;
        }
    }
}
