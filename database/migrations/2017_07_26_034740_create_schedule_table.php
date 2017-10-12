<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'schedule', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('field_id');
                $table->date('date');
                $table->text('schedule');
                $table->integer('status', false)->nullable();
                $table->integer('meeting_id', false)->nullable();
                $table->integer('customer_id', false)->nullable();
                $table->integer('time', false)->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('schedule');
    }
}
