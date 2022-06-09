<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('contact');
            $table->string('password');
            $table->string('role');
            $table->datetime('last_login_at')->nullable()->default(null);
            $table->string('last_login_ip')->nullable()->default(null);
            $table->string('confirmation_token')->nullable()->default(null);
            $table->boolean('statut_compte')->default(1);
            $table->boolean('etat_user')->default(0);
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->integer('agency_id')->nullable()->default(null);
            $table->integer('country_id')->nullable()->default(null);
            $table->integer('city_id')->nullable()->default(null);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
