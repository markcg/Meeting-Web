<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Schedule;
use App\Models\Promotion;

class FieldTest extends TestCase
{
  use DatabaseMigrations;
  use DatabaseTransactions;
  /**
  * A basic test example.
  *
  * @return void
  */
  /* Get Method */
  public function testFieldHome()
  {
    $response = $this->get('field');
    $response->assertStatus(200);
    // echo $response->getTargetUrl();
  }
  public function testFieldSchedule()
  {
    $response = $this->get('field/schedule');
    $response->assertStatus(200);
    // echo $response->getTargetUrl();
  }
  public function testFieldPromotion()
  {
    $response = $this->get('field/promotions');
    $response->assertStatus(200);
    // echo $response->getTargetUrl();
  }
  public function testFieldPromotionAdd()
  {
    $response = $this->get('field/promotions/add');
    $response->assertStatus(200);
    // echo $response->getTargetUrl();
  }
  public function testFieldPromotionEdit()
  {
    $promotion = new Promotion();
    $promotion->field_id = 10001;
    $promotion->title = 'testFieldPromotionEdit';
    $promotion->price = 0;
    $promotion->description = 'testFieldPromotionEdit';
    $promotion->save();

    $path = 'field/promotions/edit/' . $promotion->id;
    $response = $this->get($path);
    $response->assertStatus(200);

    Promotion::where('title', '=', 'testFieldPromotionEdit')->delete();
    // echo $response->getTargetUrl();
  }
  public function testFieldReport()
  {
    $response = $this->get('field/report');
    $response->assertStatus(200);
    // echo $response->getTargetUrl();
  }

  /* Post Method */
  public function testFieldPromotionAddPost()
  {
    $response = $this->post('field/promotions/add',
    [
      'title' => 'testFieldPromotionAddPost',
      'price' => 1,
      'description' => 'testFieldPromotionAddPost'
      ]
    );
    $response->assertStatus(302);
    $response->assertRedirect('field/promotions');

    Promotion::where('title', '=', 'testFieldPromotionAddPost')->delete();
    // echo $response->getTargetUrl();
  }

  public function testFieldPromotionEditPost()
  {
    $promotion = new Promotion();
    $promotion->field_id = 10002;
    $promotion->title = 'testFieldPromotionEditPost';
    $promotion->price = 0;
    $promotion->description = 'testFieldPromotionEditPost';
    $promotion->save();

    $response = $this->post('field/promotions/add',
    [
      'id' => $promotion->id,
      'title' => 'testFieldPromotionEditPostChange',
      'description' => $promotion->description,
      'price' => $promotion->price
      ]
    );
    $response->assertStatus(302);
    $response->assertRedirect('field/promotions');

    Promotion::where('title', '=', 'testFieldPromotionEditPostChange')->delete();
    // echo $response->getTargetUrl();
  }

  public function testFieldScheduleReserve()
  {
    $response = $this->post('field/schedule',
    [
      'date' => '2000-01-01',
      'time' => 1
      ]
    );
    $response->assertStatus(302);
    $response->assertRedirect('field/schedule');
    Schedule::where('date', '=', '2000-01-01')->delete();
    // echo $response->getTargetUrl();
  }

  public function testFieldScheduleCancel()
  {
    $schedule = new Schedule();
    $schedule->field_id = 9999;
    $schedule->date = '2000-01-02';
    $schedule->time = 1;
    $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
    $schedule->save();

    $response = $this->post('field/schedule',
    [
      'id' => $schedule->id,
      ]
    );
    $response->assertStatus(302);
    $response->assertRedirect('field/schedule');
    $this->assertDatabaseMissing('schedule', [
        'field_id' => 9999
    ]);
    // echo $response->getTargetUrl();
  }
}
