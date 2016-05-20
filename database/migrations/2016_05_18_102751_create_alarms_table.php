<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('event_id');
            $table->string('calendar_id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->date('alarmDate');
            $table->time('alarmTime');
            $table->string('summary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('alarms');
    }
}
