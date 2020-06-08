<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEventsTableAddWalletFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('wallet_type')->nullable()->comment('1 - Used deposited wallet only , 2 - Used bonus wallet only , 3 - Used deposited and bonus wallet both');
            $table->integer('bonus_wallet_per')->nullable()->comment('Used Bonus Wallet Per(%)');
            $table->integer('bonus_max_amt')->nullable()->comment('Used Bonus Max Amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
}
