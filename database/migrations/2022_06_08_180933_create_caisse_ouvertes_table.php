<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaisseOuvertesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caisse_ouvertes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('montant_ouverture')->unsigned();
            $table->bigInteger('solde_fermeture')->unsigned()->default(0);
            $table->bigInteger('entree')->unsigned()->default(0);
            $table->bigInteger('sortie')->unsigned()->default(0);
            $table->integer('caisse_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('observation')->nullable();
            $table->dateTime('date_ouverture');
            $table->dateTime('date_fermeture')->nullable();
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
        Schema::dropIfExists('caisse_ouvertes');
    }
}
