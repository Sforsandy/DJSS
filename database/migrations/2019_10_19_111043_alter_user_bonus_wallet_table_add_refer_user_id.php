<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserBonusWalletTableAddReferUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bonus_wallet', function (Blueprint $table) {
            $table->integer('refer_user_id')->nullable()->unsigned()->index();
            $table->foreign('refer_user_id')
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
        Schema::table('user_bonus_wallet', function (Blueprint $table) {
            //
        });
    }
}
