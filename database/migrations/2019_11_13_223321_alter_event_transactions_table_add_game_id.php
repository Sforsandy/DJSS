<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEventTransactionsTableAddGameId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_transactions', function (Blueprint $table) {
            $table->integer('game_id')->nullable()->unsigned()->index();

            $table->foreign('game_id')
            ->references('id')->on('games')
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
        Schema::table('event_transactions', function (Blueprint $table) {
            //
        });
    }
}
