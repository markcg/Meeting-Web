<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'meeting', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('customer_id');
                $table->string('name');
                $table->string('detail');
                $table->string('date');
                $table->string('start');
                $table->string('end');
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
        Schema::dropIfExists('meeting');
    }
}
