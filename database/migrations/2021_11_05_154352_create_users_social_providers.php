<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersSocialProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_social_providers', function (Blueprint $table) {
            $table->tinyInteger('provider_id')->unsigned();
            $table->bigInteger('provider_user_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('avatar')->nullable();
            $table->longText('nickname')->nullable();
            $table->string('access_token');
            $table->string('refresh_token')->nullable();
            $table->dateTime('token_expiration')->nullable();
            $table->timestamps();
        });

        Schema::table('users_social_providers', function (Blueprint $table) {
            $table->primary(['provider_id','provider_user_id']);
            $table->index(['user_id']);
            $table->index(['token_expiration']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_social_providers');
    }
}
