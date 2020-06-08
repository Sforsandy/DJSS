<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventJoinedBonusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_joined_bonus_log', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('user_id')->unsigned()->index();
           $table->integer('event_id')->nullable()->unsigned()->index();
           $table->dateTime('event_count_date');
           $table->string('bonus_type')->nullable()->comment('3_paid_event_per_day,1paid_event_consecutive_3day,5paid_event_per_week');
           $table->timestamps();

           $table->foreign('user_id')
           ->references('id')->on('users')
           ->onDelete('cascade');

           $table->foreign('event_id')
           ->references('id')->on('events')
           ->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_joined_bonus_log');
    }
}
