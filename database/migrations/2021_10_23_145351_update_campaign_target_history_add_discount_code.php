<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignTargetHistoryAddDiscountCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->string('discount_code')->after('campaign_history_id');
        });

        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->index(['campaign_id','discount_code']);
            $table->index(['campaign_history_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
