<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrderHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_order_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->unsignedFloat('order_total')->nullable();
            $table->string('browser_ip')->nullable();
            $table->string('billing_address_street')->nullable();
            $table->string('billing_address_street2')->nullable();
            $table->string('billing_address_city')->nullable();
            $table->string('billing_address_province_code')->nullable();
            $table->string('billing_address_zip')->nullable();
            $table->string('billing_address_country_code')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('shop_order_history_discount_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->string('discount_code');
            $table->timestamps();
        });

        Schema::table('shop_order_history', function (Blueprint $table) {
            $table->index(['shop_id', 'created_at']);
        });

        Schema::table('shop_order_history_discount_codes', function (Blueprint $table) {
            $table->index('order_id');
        });

        Schema::dropIfExists('client_order_history');
        Schema::dropIfExists('client_order_history_discount_codes');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_order_history');
        Schema::dropIfExists('shop_order_history_discount_codes');
    }
}
