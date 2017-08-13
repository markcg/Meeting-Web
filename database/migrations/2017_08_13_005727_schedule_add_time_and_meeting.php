<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScheduleAddTimeAndMeeting extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('schedule', function (Blueprint $table) {
      $table->integer('meeting_id', false)->nullable();
      $table->integer('time', false)->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('schedule', function (Blueprint $table) {
      $table->dropColumn('meeting_id');
      $table->dropColumn('time');
    });
  }
}
