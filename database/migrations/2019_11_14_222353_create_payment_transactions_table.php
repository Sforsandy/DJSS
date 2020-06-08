<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id',100);
            $table->integer('user_id')->unsigned()->index();
            $table->integer('event_id')->nullable()->unsigned()->index();
            $table->integer('game_id')->nullable()->unsigned()->index();
            $table->string('txn_id',200)->nullable();
            $table->decimal('txn_amount', 10, 2)->nullable()->default(0);
            $table->dateTime('txn_date')->nullable();
            $table->string('payment_mode',20)->nullable();
            $table->string('currency',20)->nullable();
            $table->string('status',50)->nullable();
            $table->string('resp_code',50)->nullable();
            $table->string('resp_msg',150)->nullable();
            $table->string('gateway_name',150)->nullable();
            $table->string('bank_txn_id',150)->nullable();
            $table->string('bank_name',250)->nullable();
            $table->text('check_sum_hash')->nullable();
            $table->text('full_response')->nullable();

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
        Schema::dropIfExists('payment_transactions');
    }
}
