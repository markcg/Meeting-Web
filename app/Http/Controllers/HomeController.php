<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Promotion;
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
  public function schedule()
  {
    return view('field.schedule');
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
}
