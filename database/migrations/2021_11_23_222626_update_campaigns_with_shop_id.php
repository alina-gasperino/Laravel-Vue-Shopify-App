<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignsWithShopId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Added some additional functionality around shops. It seems we'll have some problems if someone signs up
        // with the same email

        Schema::table('campaigns', function (Blueprint $table) {
            $table->bigInteger('shop_id')->unsigned()->after('id');
            $table->index('shop_id');
        });

        //This data is a bit redundant however we need it the child table campaign_target_histor so added it here for consistenty
        Schema::table('campaign_history', function (Blueprint $table) {
            $table->bigInteger('shop_id')->unsigned()->after('id');
            $table->index('shop_id');
        });


        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->bigInteger('shop_id')->unsigned()->after('id');
            $table->dropIndex('campaign_target_history_campaign_id_discount_code_index');
            $table->dropUnique('campaign_target_history_campaign_id_discount_code_unique');
            $table->unique(['shop_id', 'discount_code'], 'unique_discount_code');
        });


        Schema::table('shops', function (Blueprint $table) {
            //This information is redundant as it lives in users_social_providers. It's added here for clarity on what it is.
            $table->bigInteger('external_shop_id')->unsigned()->after('id');
            $table->index('external_shop_id');
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
            $table->dropColumn('shop_id');
        });

        //This data is a bit redundant however we need it the child table campaign_target_histor so added it here for consistenty
        Schema::table('campaign_history', function (Blueprint $table) {
            $table->dropColumn('shop_id');
        });


        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->dropColumn('shop_id');
            $table->unique(['campaign_id', 'discount_code']);
        });


        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('external_shop_id');
        });
    }
}
