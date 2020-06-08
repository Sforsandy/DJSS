<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDepositedWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_deposited_wallet', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('event_id')->nullable()->unsigned()->index();
            $table->integer('game_id')->nullable()->unsigned()->index();
            $table->decimal('amount', 10, 2)->nullable()->default(0);
            $table->dateTime('txn_date');
            $table->enum('txn_type',['0','1'])->comment('0 - credit , 1 - debit');
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');

            $table->foreign('event_id')
            ->references('id')->on('events')
            ->onDelete('cascade');

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
        Schema::dropIfExists('user_deposited_wallet');
    }
}
