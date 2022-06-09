<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTauxTransfertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taux_transferts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("montant_minimum");
            $table->bigInteger("montant_maximum");
            $table->integer("montant_fixe")->nullable();
            $table->float("taux",8,4)->nullable();
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
        Schema::dropIfExists('taux_transferts');
    }
}
