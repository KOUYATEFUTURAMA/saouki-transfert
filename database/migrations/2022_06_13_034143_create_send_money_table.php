<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_money', function (Blueprint $table) {
            $table->id();
            $table->string("secret_code");
            $table->dateTime("send_date");
            $table->string("state"); //sent, withdrawn
            $table->integer("sender_id")->unsigned();
            $table->integer("recipient_id")->unsigned();
            $table->integer("sending_country_id")->unsigned(); 
            $table->integer("destination_country_id")->unsigned();
            $table->bigInteger("amount")->unsigned();
            $table->bigInteger("shipping_cost")->unsigned();
            $table->bigInteger("discount_on_shipping_costs")->unsigned()->default(0);
            $table->boolean("shipping_costs_included")->default(0);
            $table->boolean("to_delete")->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('send_money');
    }
}
