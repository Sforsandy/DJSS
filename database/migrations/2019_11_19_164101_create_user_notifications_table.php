<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('event_id')->nullable()->unsigned()->index();
            $table->enum('is_read',['0','1'])->comment('0 - Unread , 1 - Read');
            $table->enum('is_redirect',['0','1'])->comment('0 - No , 1 - Yes');
            $table->dateTime('send_date');
            $table->text('notification_title')->nullable();
            $table->string('notification_desc')->nullable();
            $table->string('notification_type')->nullable()->comment('event_joined,event_access_detail,event_end,new_event_post,event_won,unlocked_new_lavel,wallet_balance_updated');
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
        Schema::dropIfExists('user_notifications');
    }
}
