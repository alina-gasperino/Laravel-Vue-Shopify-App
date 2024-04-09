<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('campaign_id')->unsigned();
            $table->tinyInteger('state')->unsigned();
            $table->bigInteger('count_unique_ip')->unsigned()->nullable();
            $table->bigInteger('count_mailers_sent')->unsigned()->nullable();
            $table->float('cost_of_send')->unsigned()->nullable();
            $table->float('revenue_from_mailers')->unsigned()->nullable();
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
        Schema::dropIfExists('campaign_history');
    }
}
