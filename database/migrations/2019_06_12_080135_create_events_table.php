<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_name',150);
            $table->text('event_description');
            $table->integer('event_type')->unsigned()->index();
            $table->integer('event_format')->unsigned()->index();
            $table->integer('game')->unsigned()->index();
            $table->integer('capacity')->nullable()->default(0);
            $table->integer('fee')->nullable()->default(0);
            $table->date('schedule_date');
            $table->time('schedule_time');
            $table->dateTime('schedule_datetime')->nullable();
            $table->integer('created_by')->unsigned()->index();
            $table->text('access_details')->nullable();
            $table->string('stream_url',250)->nullable();
            $table->boolean('status')->default(0)->index();

            $table->integer('total_prize')->nullable()->default(0);
            $table->integer('winner_prize')->nullable()->default(0);
            $table->integer('runner_up1_prize')->nullable()->default(0);
            $table->integer('runner_up2_prize')->nullable()->default(0);
            $table->timestamps();


            $table->foreign('event_type')
            ->references('id')->on('event_types')
            ->onDelete('cascade');

            $table->foreign('event_format')
            ->references('id')->on('event_formats')
            ->onDelete('cascade');

             $table->foreign('game')
            ->references('id')->on('games')
            ->onDelete('cascade');

            $table->foreign('created_by')
            ->references('id')->on('users')
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
        Schema::dropIfExists('events');
    }
}
