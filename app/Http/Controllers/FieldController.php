<?php

namespace App\Http\Controllers;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Promotion;
use App\Models\Schedule;

class FieldController extends Controller
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
  public function edit_promotion_list($id)
  {
    $promotion = Promotion::find($id);
    if(is_null($promotion)){
      return redirect('/field/promotions');
    } else {
      return view('field.promotion_add', [ 'promotion' =>  $promotion, 'id' => $id]);
    }
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
    if($this->promotion_add($request)){
      return redirect('/field/promotions');
    } else {
      return redirect('/field/promotions');
    }
  }

  public function edit_promotion(Request $request)
  {
    $id = $request->input('id');
    if($this->promotion_edit($id, $request)){
      return redirect('/field/promotions');
    } else {
      return redirect('/field/promotions');
    }
  }

  public function handle_schedule(Request $request)
  {
    $id = $request->input('id');
    if( $id === null){
      $this->schedule_reserve($request);
    } else{
      $this->schedule_delete($id);
    }
    if($request->has('search')){
      $redirect = '/field/schedule?search=' . $request->input('search');
    } else {
      $redirect = '/field/schedule';
    }
    return redirect($redirect);
  }

  /* API Method */
  /* --Promotion*/
  public function promotion_add(Request $request){
    try{
      $promotion = new Promotion();
      $promotion->field_id = 1;
      $promotion->title = $request->input('title');
      $promotion->price = $request->input('price');
      $promotion->description = $request->input('description');
      $promotion->save();
      return true;
    }catch(\Exception $e){
      return false;
    }
  }
  public function promotion_edit($id, Request $request){
    try{
      $promotion = Promotion::find($id);
      $promotion->field_id = 1;
      $promotion->title = $request->input('title');
      $promotion->price = $request->input('price');
      $promotion->description = $request->input('description');
      $promotion->save();
      return true;
    }catch(\Exception $e){
      return false;
    }
  }

  /* --Schedule */
  public function schedule_add(Request $request){
    try{
      $schedule = new Schedule();
      $schedule->field_id = 1;
      $schedule->date = $request->input('date');
      $schedule->time = $request->input('time');
      $schedule->schedule = $request->input('schedule');
      $schedule->save();
      return true;
    }catch(\Exception $e){
      return false;
    }
  }
  public function schedule_delete($id){
    try{
      $schedule = Schedule::find($id);
      if(is_null($schedule)){
        return false;
      } else {
        $schedule->delete();
      }
      return true;
    }catch(\Exception $e){
      return false;
    }
  }
  public function schedule_confirm(){

  }
  public function schedule_reserve(Request $request){
    try{
      $schedule = new Schedule();
      $schedule->field_id = 1;
      $schedule->date = $request->input('date');
      $schedule->time = $request->input('time');
      $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
      $schedule->save();
      return true;
    }catch(\Exception $e){
      return false;
    }
  }
}
