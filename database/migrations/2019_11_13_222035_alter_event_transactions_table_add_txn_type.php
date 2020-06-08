<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEventTransactionsTableAddTxnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_transactions', function (Blueprint $table) {
            $table->string('txn_title')->nullable()->comment('joined_event ,won_event ,promotional_bonus ,referrel_bonus ,balance_credit, balance_debit');
            $table->enum('txn_type',['0','1'])->comment('0 - credit , 1 - debit');
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
