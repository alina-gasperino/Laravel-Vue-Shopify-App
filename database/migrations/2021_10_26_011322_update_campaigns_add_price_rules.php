<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignsAddPriceRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('discount_prefix')->after('audience_size_id');
            $table->tinyInteger('discount_type')->unsigned()->after('audience_size_id');
            $table->float('discount_amount')->after('audience_size_id');
            $table->bigInteger('discount_price_rule_id')->unsigned()->after('audience_size_id');
        });

        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->unique(['campaign_id', 'discount_code']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('discount_prefix');
            $table->dropColumn('discount_type');
            $table->dropColumn('discount_amount');
            $table->dropColumn('discount_price_rule_id');
        });

        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->dropIndex('campaign_target_history_campaign_id_discount_code');
        });

    }
}
