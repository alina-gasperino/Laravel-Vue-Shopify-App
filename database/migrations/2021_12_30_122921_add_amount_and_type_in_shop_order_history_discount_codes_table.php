<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountAndTypeInShopOrderHistoryDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_order_history_discount_codes', function (Blueprint $table) {
            $table->decimal('discount_amount',12,2)->after('discount_code');
            $table->string('discount_type')->after('discount_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_order_history_discount_codes', function (Blueprint $table) {
            $table->dropColumn(['discount_amount','discount_type']);
        });
    }
}
