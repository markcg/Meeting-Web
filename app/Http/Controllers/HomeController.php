<?php

namespace App\Http\Controllers;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Promotion;
use App\Models\Schedule;

class HomeController extends Controller
{
  /* Get Method */
  public function home()
  {
    return view('field.home');
  }
  public function promotions()
  {
    $field = Field::first();
    $promotions = $field->promotions;
    return view('field.promotion', [ "promotions" => $promotions ]);
  }
  public function add_promotion_list()
  {
    return view('field.promotion_add');
  }
  public function update_promotion_list($id)
  {
    $promotion = Promotion::find($id);
    return view('field.promotion_add', [ 'promotion' =>  $promotion]);
  }
  public function schedule(Request $request)
  {
    $date = date('Y-m-d');
    if($request->has('search')){
      $date = $request->input('search');
    }
    $reserved = [];

    for($i = 0; $i < 7; $i++){
      $next = DateTime::createFromFormat('Y-m-d', $date);
      $next->modify('+' . $i . ' day');
      $formatted = $next->format('Y-m-d');
      $data = Schedule::where('date', '=', $formatted)->orderBy('time', 'desc')->get();
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
  public function add_promotion(Request $request)
  {
    $id = $request->input('id');
    if( $id === null){
      $promotion = new Promotion();
    } else{
      $promotion = Promotion::find($id);
    }
    $promotion->field_id = 1;
    $promotion->title = $request->input('title');
    $promotion->price = $request->input('price');
    $promotion->description = $request->input('description');
    $promotion->save();
    return redirect('/field/promotions');
  }

  public function handle_schedule(Request $request)
  {
    $id = $request->input('id');
    if( $id === null){
      $schedule = new Schedule();
      $schedule->field_id = 1;
      $schedule->date = $request->input('date');
      $schedule->time = $request->input('time');
      $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
      $schedule->save();
    } else{
      $schedule = Schedule::find($id);
      $schedule->delete();
    }
    if($request->has('search')){
      $redirect = '/field/schedule?date=' . $request->input('search');
    } else {
      $redirect = '/field/schedule';
    }
    return redirect($redirect);
  }

  /* API Method */
  public function add_schedule(){

  }
  public function delete_schedule(){

  }
  public function confirm_schedule(){

  }
  public function reserve_schedule(){

  }
}
