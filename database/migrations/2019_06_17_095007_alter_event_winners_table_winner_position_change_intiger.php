<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEventWinnersTableWinnerPositionChangeIntiger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_winners', function (Blueprint $table) {
            // $table->integer('winner_position')->change()->unsigned()->index();

            //  $table->foreign('winner_position')
            // ->references('id')->on('winner_positions')
            // ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_winners', function (Blueprint $table) {
            //
        });
    }
}
