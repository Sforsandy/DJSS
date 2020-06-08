<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserBonusWalletTableAddBonusType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bonus_wallet', function (Blueprint $table) {
            $table->string('bonus_type')->nullable()->comment('sign_with_refer_code,referrer_earn,3_paid_event_per_day,1paid_event_consecutive_3day,5paid_event_per_week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bonus_wallet', function (Blueprint $table) {
            //
        });
    }
}
