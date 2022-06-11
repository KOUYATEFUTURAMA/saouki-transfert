<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string("reference");
            $table->string("operation_type"); //withdrawal or deposit
            $table->bigInteger("amount");
            $table->dateTime("date");
            $table->string("state"); //0 : recorded, 1 : authorized, 2 : unauthorized 
            $table->integer("partenair_id")->nullable();
            $table->string("receptionist")->nullable();
            $table->string("id_card_receptionist")->nullable();
            $table->integer("bank_id")->nullable();
            $table->integer("city_id")->nullable();
            $table->integer('caisse_ouverte_id')->unsigned();
            $table->integer("other_caisse_id")->nullable();
            $table->integer("user_id")->nullable();
            $table->integer("authorized_by")->nullable();
            $table->dateTime("authorization_date")->nullable();
            $table->text("observation")->nullable();
            $table->string("file_to_upload")->nullable();
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
        Schema::dropIfExists('operations');
    }
}
