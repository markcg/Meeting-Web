<?php

namespace App\Http\Controllers;

use Validator;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $messages = [
        'username.alpha_dash' => 'Username is incorrect format. Please use only a-z, A-Z and 0-9',
        'password.required'  => 'A message is required',
        ];
        $validator = Validator::make(
            $request->all(), [
            'username' => 'required|alpha_dash|max:10',
            'password' => 'required',
            ],
            $messages
        );

        if ($validator->fails()) {
            return redirect('/field/login')
                      ->withErrors($validator)
                      ->withInput();
        }

        $login = $this->account_login($request);
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
            return redirect('/field/change-password')
                  ->withErrors($validator)
                  ->withInput();
        }

        if($request->input('new_password') !== $request->input('re_password')) {
            return redirect('/field/change-password')
            ->withErrors(['Password and Re-Password are not match.'])
            ->withInput();;
        }

        $model = $this->account_change_password($request);
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
        $model = $this->account_register($request);
        if (!empty($model)) {
            return redirect('/field/login');
        } else {
            return redirect('/field/register');
        }
    }

    public function handle_edit(Request $request)
    {
        $model = $this->account_edit($request);
        echo var_dump($model);
        if (!empty($model)) {
            session(['field' => $model]);
            return redirect('/field/edit');
        } else {
            return redirect('/field/edit');
        }
    }
    public function promotion_rule()
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
    public function add_promotion(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'title' => 'required|alpha_dash|min:4|max:30',
            'price' => 'required|numeric|min:10|max:9999999999',
            'description' => 'required|alpha_dash|min:10|max:250',
            ],
            $this->promotion_rule()
        );
        if ($validator->fails()) {
            return redirect('/field/promotions/add')
                    ->withErrors($validator)
                    ->withInput();
        }

        if ($this->promotion_add($request)) {
            return redirect('/field/promotions');
        } else {
            return redirect('/field/promotions/add')
            ->withErrors($validator)
            ->withInput();;
        }
    }

    public function edit_promotion(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'title' => 'required|alpha_dash|min:4|max:30',
            'price' => 'required|numeric|min:2|max:10',
            'description' => 'required|alpha_dash|min:10|max:250',
            ],
            $this->promotion_rule()
        );

        if ($validator->fails()) {
            return redirect('/field/promotions/edit/' . $request->input('id'))
                  ->withErrors($validator)
                  ->withInput();
        }

        $id = $request->input('id');
        if ($this->promotion_edit($id, $request)) {
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
    public function account_login(Request $request)
    {
        try {
            $username = $request->input('username');
            $password = $request->input('password');
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

    public function account_register(Request $request)
    {
        try {
            $account = new Field();
            $account->name = $request->input('name');
            $account->description = $request->input('description');
            $account->email = $request->input('email');
            $account->address = $request->input('address');
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
            $account = Field::find($request->input('id'));
            if(is_null($account)) {
                return false;
            } else {
                $account->name = $request->input('name');
                $account->description = $request->input('description');
                $account->email = $request->input('email');
                $account->address = $request->input('address');
                $account->phone_number = $request->input('phone_number');
                $account->username = $request->input('username');
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
            $user = Field::find($request->input('id'));
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

    public function account_forget(Request $request, $no_mail = false)
    {
        try {
            $account = Field::where('username', '=', $request->input('username'))->first();
            if(is_null($account)) {
                return false;
            } else if($account->email !== $request->input('email')) {
                return false;
            }else {
                if(!$no_mail) {
                    $tempPassword = str_random(10);
                    $message = 'Your new password is ' . $tempPassword;
                    Mail::raw(
                        $message, $account, function ($message) {
                            $message->from('fieldfinder.mailserver@gmail.com', 'Field Finder Forget Password');
                            $message->to($request->input('email'));
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
    public function promotion_add(Request $request)
    {
        try {
            $promotion = new Promotion();
            $promotion->field_id = $request->input('field_id');
            $promotion->title = $request->input('title');
            $promotion->price = $request->input('price');
            $promotion->description = $request->input('description');
            $promotion->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function promotion_edit($id, Request $request)
    {
        try {
            $promotion = Promotion::find($id);
            $promotion->title = $request->input('title');
            $promotion->price = $request->input('price');
            $promotion->description = $request->input('description');
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
    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
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
