<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
             $table->increments('id');
             $table->string('promocode',20);
             $table->integer('amount')->default(0);
             $table->integer('user_id')->nullable()->unsigned()->index();
             $table->integer('used_per_user')->default('1');
             $table->enum('credit_wallat_type',['1','2','3'])->comment('1 - Deposited , 2 - Winnings , 3 - Bonus');
             $table->date('expire_date');
            $table->timestamps();

            $table->foreign('user_id')
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
        Schema::dropIfExists('promo_codes');
    }
}
