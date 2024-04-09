<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTargetHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_target_history', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('campaign_history_id')->unsigned();
            $table->string('user_ip');
            $table->bigInteger('external_user_id')->unsigned()->nullable();
            $table->tinyInteger('match_found')->unsigned()->default(0);
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
        Schema::dropIfExists('campaign_target_history');
    }
}
