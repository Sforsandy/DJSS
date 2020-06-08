<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id',100);
            $table->integer('user_id')->unsigned()->index();
            $table->integer('event_id')->nullable()->unsigned()->index();
            $table->integer('game_id')->nullable()->unsigned()->index();
            $table->decimal('txn_amount', 10, 2)->nullable()->default(0);
            $table->dateTime('txn_date')->nullable();
            $table->string('txn_title')->nullable()->comment('joined_event ,won_event ,promotional_bonus ,referrel_bonus ,balance_credit, balance_debit');
            $table->enum('txn_type',['0','1'])->comment('0 - credit , 1 - debit');
            $table->string('status',50)->nullable()->comment('success ,fail ,pending');

            $table->timestamps();

            $table->foreign('game_id')
            ->references('id')->on('games')
            ->onDelete('cascade');

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
        Schema::dropIfExists('user_transactions');
    }
}
