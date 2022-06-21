<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_money', function (Blueprint $table) {
            $table->id();
            $table->dateTime("withdrawal_date");
            $table->integer("send_money_id");
            $table->bigInteger("amount");
            $table->string("id_card_recipient")->nullable();
            $table->integer('created_by')->unsigned()->nullable();//agent
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawal_money');
    }
}
