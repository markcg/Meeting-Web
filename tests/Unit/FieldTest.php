<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

use App\Http\Controllers\FieldController;
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
    $this->assertDatabaseHas('promotion', [
        'title' => 'testFieldPromotionAddPost'
    ]);

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

    Promotion::where('title', '=', 'testFieldPromotionEditPost')->delete();
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

    /* API Method*/
    /* --Promotion Add*/
    public function testFieldAPIAddPromotionValid()
    {
      $controller = new FieldController();
      $request = Request::create(
      '/promotions/add',
      'post',
      [
        'title' => 'testFieldAPIAddPromotionValid',
        'price' => '0',
        'description' => 'testFieldAPIAddPromotionValid'
        ]
      );
      $result = $controller->promotion_add($request);
      $this->assertTrue($result);
      $this->assertDatabaseHas('promotion', [
          'title' => 'testFieldAPIAddPromotionValid'
      ]);
      Promotion::where('title', '=', 'testFieldAPIAddPromotionValid')->delete();
    }

    public function testFieldAPIAddPromotionInvalid()
    {
      $controller = new FieldController();
      $request = Request::create(
      '/promotions/add',
      'post',
      [
        'title' => 'API Add'
        ]
      );
      $result = $controller->promotion_add($request);
      $this->assertFalse($result);
    }
    /* -- Promotion Edit */
    public function testFieldAPIEditPromotionValid()
    {
      $promotion = new Promotion();
      $promotion->field_id = 10003;
      $promotion->title = 'testFieldAPIEditPromotionValid';
      $promotion->price = 0;
      $promotion->description = 'testFieldAPIEditPromotionValid';
      $promotion->save();

      $controller = new FieldController();
      $request = Request::create(
      '/promotions/edit',
      'post',
      [
        'title' => 'testFieldAPIEditPromotionValidChange',
        'price' => '0',
        'description' => 'testFieldAPIEditPromotionValid'
        ]
      );
      $result = $controller->promotion_edit($promotion->id, $request);
      $this->assertTrue($result);
      $this->assertDatabaseHas('promotion', [
          'title' => 'testFieldAPIEditPromotionValidChange'
      ]);
      Promotion::where('title', '=', 'testFieldAPIEditPromotionValid')->delete();
    }

    public function testFieldAPIEditPromotionInvalid()
    {
      $controller = new FieldController();
      $request = Request::create(
      '/promotions/edit',
      'post',
      [
        'title' => 'testFieldAPIEditPromotionValid',
        ]
      );
      $result = $controller->promotion_edit(null, $request);
      $this->assertFalse($result);
    }

    /* -- Schedule Reserve */
    public function testFieldAPIReserveScheduleValid()
    {
      $controller = new FieldController();
      $request = Request::create(
      '/promotions/schedule',
      'post',
      [
        'date' => '2000-01-03',
        'time' => 0,
        ]
      );
      $result = $controller->schedule_reserve($request);
      $this->assertTrue($result);
      $this->assertDatabaseHas('schedule', [
          'date' => '2000-01-03'
      ]);
      Schedule::where('date', '=', '2000-01-03')->delete();
    }

    public function testFieldAPIReserveScheduleInvalid()
    {
      $controller = new FieldController();
      $request = Request::create(
      '/promotions/schedule',
      'post',
      []
      );
      $result = $controller->schedule_reserve($request);
      $this->assertFalse($result);
    }

    /* -- Schedule Delete */
    public function testFieldAPIRemoveScheduleValid()
    {
      $schedule = new Schedule();
      $schedule->field_id = 9999;
      $schedule->date = '2000-01-05';
      $schedule->time = 1;
      $schedule->schedule = 'Reserve by field at ' . date('Y-m-d H:i:s');
      $schedule->save();

      $controller = new FieldController();
      $result = $controller->schedule_delete($schedule->id);
      $this->assertTrue($result);
      $this->assertDatabaseMissing('schedule', [
          'date' => '2000-01-05'
      ]);
      // Schedule::where('date', '=', '2000-01-03')->delete();
    }

    public function testFieldAPIRemoveScheduleInvalid()
    {
      $controller = new FieldController();
      $result = $controller->schedule_delete(null);
      $this->assertFalse($result);
    }
  }
