<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientOrderHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_order_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->unsignedFloat('order_total')->nullable();
            $table->string('browser_ip')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('client_order_history_discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('discount_code');
            $table->timestamps();
        });

        Schema::table('client_order_history', function (Blueprint $table) {
            $table->index('user_id');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_order_history');
        Schema::dropIfExists('client_order_history_discount_codes');
    }
}
