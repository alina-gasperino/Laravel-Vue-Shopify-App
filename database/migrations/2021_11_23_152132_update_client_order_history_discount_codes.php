<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientOrderHistoryDiscountCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_order_history_discount_codes', function (Blueprint $table) {
            $table->bigInteger('order_id')->unsigned()->after('id');
        });

        Schema::table('client_order_history_discount_codes', function (Blueprint $table) {
            $table->index(['order_id'], 'order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_order_history_discount_codes', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });

        Schema::table('client_order_history_discount_codes', function (Blueprint $table) {
            $table->dropIndex('order_id');
        });
    }
}
