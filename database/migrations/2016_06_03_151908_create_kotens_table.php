<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKotensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kotens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('kot_id');
            $table->string('street');
            $table->string('city');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('device_koten',function(Blueprint $table){
            $table->integer('device_id')->unsigned()->index();
//            $table->foreign('device_id')->reference('id')->on('devices')->onDelete('cascade');

            $table->integer('koten_id')->unsigned()->index();
//            $table->foreign('koten_id')->reference('id')->on('kotens')->onDelete('cascade');

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
        Schema::drop('kotens');
        Schema::drop('device_koten');
    }
}
