<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_history', function (Blueprint $table) {
            $table->renameColumn('revenue_from_mailers', 'total_revenue');
        });

        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->float('total_revenue')->unsigned()->nullable()->after('discount_code');
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
